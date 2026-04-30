<?php
/**
 * GitHub changelog synchronization for theme projects.
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Synchronize and normalize changelog data from GitHub.
 */
class Jardin_Projects_Sync {
	const CRON_HOOK = 'jardin_projects_sync_cron';

	const CACHE_TTL = HOUR_IN_SECONDS;

	const MAX_ITEMS = 20;

	public function __construct() {
		add_action( self::CRON_HOOK, array( $this, 'sync_all_auto_projects' ) );
	}

	public static function ensure_cron(): void {
		if ( wp_next_scheduled( self::CRON_HOOK ) ) {
			return;
		}
		wp_schedule_event( time() + 60, 'hourly', self::CRON_HOOK );
	}

	public function sync_all_auto_projects(): void {
		$query = new WP_Query(
			array(
				'post_type'      => jardin_projects_get_post_type(),
				'post_status'    => 'publish',
				'posts_per_page' => 50,
				'fields'         => 'ids',
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => 'sync_mode',
						'value'   => 'manual',
						'compare' => '!=',
					),
					array(
						'key'     => 'sync_mode',
						'compare' => 'NOT EXISTS',
					),
				),
			)
		);

		foreach ( $query->posts as $project_id ) {
			$this->sync_project( (int) $project_id, 'cron' );
		}
	}

	/**
	 * Sync one project and persist normalized data.
	 *
	 * @param int    $project_id Project ID.
	 * @param string $source     Sync source for logging.
	 * @return array<string, mixed>
	 */
	public function sync_project( $project_id, $source = 'manual' ) {
		$project_id = (int) $project_id;
		$repo_url   = (string) get_post_meta( $project_id, 'repo_url', true );

		if ( '' === trim( $repo_url ) ) {
			return $this->mark_sync_error( $project_id, __( 'Repository URL is missing.', 'jardin-theme' ), $source );
		}

		$repo = $this->extract_repo_path( $repo_url );
		if ( '' === $repo ) {
			return $this->mark_sync_error( $project_id, __( 'Invalid GitHub repository URL.', 'jardin-theme' ), $source );
		}

		$result = $this->fetch_releases( $repo );
		if ( empty( $result['items'] ) ) {
			$result = $this->fetch_tags( $repo );
		}
		if ( empty( $result['items'] ) ) {
			$result = $this->fetch_changelog_file( $repo );
		}

		if ( empty( $result['items'] ) ) {
			return $this->mark_sync_error(
				$project_id,
				isset( $result['error'] ) ? (string) $result['error'] : __( 'No changelog entries found.', 'jardin-theme' ),
				$source
			);
		}

		$items = array_slice( $result['items'], 0, self::MAX_ITEMS );
		update_post_meta( $project_id, '_jardin_project_changelog', $items );
		update_post_meta( $project_id, '_jardin_project_sync_state', 'ok' );
		update_post_meta( $project_id, '_jardin_project_sync_source', sanitize_key( (string) $result['source'] ) );
		update_post_meta( $project_id, '_jardin_project_last_sync_at', gmdate( 'c' ) );
		update_post_meta( $project_id, '_jardin_project_last_error', '' );
		delete_transient( $this->get_changelog_transient_key( $project_id ) );

		return array(
			'state' => 'ok',
			'count' => count( $items ),
			'items' => $items,
		);
	}

	/**
	 * Read normalized changelog from persistent storage.
	 *
	 * @param int $project_id Project ID.
	 * @return array<int, array<string, string>>
	 */
	public function get_project_changelog( $project_id ) {
		$project_id = (int) $project_id;
		$key        = $this->get_changelog_transient_key( $project_id );
		$cached     = get_transient( $key );
		if ( is_array( $cached ) ) {
			return $cached;
		}

		$stored = get_post_meta( $project_id, '_jardin_project_changelog', true );
		$items  = is_array( $stored ) ? $stored : array();
		set_transient( $key, $items, self::CACHE_TTL );
		return $items;
	}

	private function get_changelog_transient_key( int $project_id ): string {
		return 'jardin_project_changelog_' . $project_id;
	}

	private function mark_sync_error( int $project_id, string $message, string $source ): array {
		update_post_meta( $project_id, '_jardin_project_sync_state', 'error' );
		update_post_meta( $project_id, '_jardin_project_sync_source', sanitize_key( $source ) );
		update_post_meta( $project_id, '_jardin_project_last_error', sanitize_text_field( $message ) );
		update_post_meta( $project_id, '_jardin_project_last_error_at', gmdate( 'c' ) );

		return array(
			'state' => 'error',
			'error' => $message,
		);
	}

	private function extract_repo_path( string $repo_url ): string {
		$url = wp_parse_url( trim( $repo_url ) );
		if ( ! is_array( $url ) ) {
			return '';
		}
		$host = isset( $url['host'] ) ? strtolower( (string) $url['host'] ) : '';
		if ( '' === $host || false === strpos( $host, 'github.com' ) ) {
			return '';
		}
		$path = isset( $url['path'] ) ? trim( (string) $url['path'], '/' ) : '';
		if ( '' === $path ) {
			return '';
		}
		$parts = array_values( array_filter( explode( '/', $path ) ) );
		if ( count( $parts ) < 2 ) {
			return '';
		}
		return sanitize_text_field( $parts[0] . '/' . $parts[1] );
	}

	private function github_json_request( string $url ): array {
		$ua       = jardin_theme_projects_user_agent();
		$response = wp_remote_get(
			$url,
			array(
				'timeout' => 8,
				'headers' => array(
					'Accept'     => 'application/vnd.github+json',
					'User-Agent' => $ua,
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return array( 'error' => $response->get_error_message() );
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		$body = (string) wp_remote_retrieve_body( $response );
		if ( $code < 200 || $code >= 300 ) {
			return array( 'error' => sprintf( 'GitHub API HTTP %d', $code ) );
		}

		$data = json_decode( $body, true );
		if ( null === $data && JSON_ERROR_NONE !== json_last_error() ) {
			return array( 'error' => 'Invalid JSON response' );
		}

		return array( 'data' => $data );
	}

	private function fetch_releases( string $repo ): array {
		$url      = 'https://api.github.com/repos/' . $repo . '/releases?per_page=20';
		$response = $this->github_json_request( $url );
		if ( isset( $response['error'] ) ) {
			return array(
				'source' => 'release',
				'items'  => array(),
				'error'  => (string) $response['error'],
			);
		}

		$data  = is_array( $response['data'] ) ? $response['data'] : array();
		$items = array();
		foreach ( $data as $release ) {
			if ( ! is_array( $release ) ) {
				continue;
			}
			$version = isset( $release['tag_name'] ) ? sanitize_text_field( (string) $release['tag_name'] ) : '';
			if ( '' === $version ) {
				continue;
			}
			$title = isset( $release['name'] ) ? sanitize_text_field( (string) $release['name'] ) : '';
			$body  = isset( $release['body'] ) ? wp_strip_all_tags( (string) $release['body'] ) : '';
			$items[] = array(
				'version_tag'  => $version,
				'published_at' => isset( $release['published_at'] ) ? sanitize_text_field( (string) $release['published_at'] ) : '',
				'summary'      => $this->summarize_release( $title, $body ),
				'release_url'  => isset( $release['html_url'] ) ? esc_url_raw( (string) $release['html_url'] ) : '',
				'source_type'  => 'release',
			);
		}

		return array(
			'source' => 'release',
			'items'  => $items,
		);
	}

	private function fetch_tags( string $repo ): array {
		$url      = 'https://api.github.com/repos/' . $repo . '/tags?per_page=20';
		$response = $this->github_json_request( $url );
		if ( isset( $response['error'] ) ) {
			return array(
				'source' => 'tag',
				'items'  => array(),
				'error'  => (string) $response['error'],
			);
		}

		$data  = is_array( $response['data'] ) ? $response['data'] : array();
		$items = array();
		foreach ( $data as $tag ) {
			if ( ! is_array( $tag ) ) {
				continue;
			}
			$version = isset( $tag['name'] ) ? sanitize_text_field( (string) $tag['name'] ) : '';
			if ( '' === $version ) {
				continue;
			}
			$url = isset( $tag['commit']['url'] ) ? esc_url_raw( (string) $tag['commit']['url'] ) : '';
			$items[] = array(
				'version_tag'  => $version,
				'published_at' => '',
				'summary'      => __( 'Git tag detected (no published release note).', 'jardin-theme' ),
				'release_url'  => $url,
				'source_type'  => 'tag',
			);
		}

		return array(
			'source' => 'tag',
			'items'  => $items,
		);
	}

	private function fetch_changelog_file( string $repo ): array {
		$contents_url = 'https://api.github.com/repos/' . $repo . '/contents/CHANGELOG.md';
		$response     = $this->github_json_request( $contents_url );
		if ( isset( $response['error'] ) ) {
			return array(
				'source' => 'parsed_file',
				'items'  => array(),
				'error'  => (string) $response['error'],
			);
		}

		$data = is_array( $response['data'] ) ? $response['data'] : array();
		$raw  = isset( $data['download_url'] ) ? esc_url_raw( (string) $data['download_url'] ) : '';
		if ( '' === $raw ) {
			return array(
				'source' => 'parsed_file',
				'items'  => array(),
				'error'  => __( 'CHANGELOG.md was found but cannot be downloaded.', 'jardin-theme' ),
			);
		}

		$ua            = jardin_theme_projects_user_agent();
		$file_response = wp_remote_get(
			$raw,
			array(
				'timeout' => 8,
				'headers' => array( 'User-Agent' => $ua ),
			)
		);
		if ( is_wp_error( $file_response ) ) {
			return array(
				'source' => 'parsed_file',
				'items'  => array(),
				'error'  => $file_response->get_error_message(),
			);
		}

		$content = (string) wp_remote_retrieve_body( $file_response );
		$items   = $this->parse_markdown_changelog( $content, $raw );
		return array(
			'source' => 'parsed_file',
			'items'  => $items,
		);
	}

	private function parse_markdown_changelog( string $content, string $source_url ): array {
		$lines = preg_split( '/\r\n|\r|\n/', $content );
		if ( ! is_array( $lines ) ) {
			return array();
		}

		$items         = array();
		$current_tag   = '';
		$current_date  = '';
		$current_lines = array();

		$flush = function () use ( &$items, &$current_tag, &$current_date, &$current_lines, $source_url ) {
			if ( '' === $current_tag ) {
				return;
			}
			$summary = trim( implode( ' ', array_slice( $current_lines, 0, 2 ) ) );
			$items[] = array(
				'version_tag'   => sanitize_text_field( $current_tag ),
				'published_at'  => sanitize_text_field( $current_date ),
				'summary'       => '' !== $summary ? wp_strip_all_tags( $summary ) : __( 'Parsed from CHANGELOG.md.', 'jardin-theme' ),
				'release_url'   => $source_url,
				'source_type'   => 'parsed_file',
			);
		};

		foreach ( $lines as $line ) {
			if ( preg_match( '/^##\s+\[?([^\]\s]+)\]?(?:\s*-\s*([0-9]{4}-[0-9]{2}-[0-9]{2}))?/u', (string) $line, $m ) ) {
				$flush();
				$current_tag   = isset( $m[1] ) ? (string) $m[1] : '';
				$current_date  = isset( $m[2] ) ? (string) $m[2] : '';
				$current_lines = array();
				continue;
			}
			if ( '' !== $current_tag ) {
				$clean = trim( preg_replace( '/^[\-\*\s]+/', '', (string) $line ) );
				if ( '' !== $clean ) {
					$current_lines[] = $clean;
				}
			}
		}
		$flush();

		return array_slice( $items, 0, self::MAX_ITEMS );
	}

	private function summarize_release( string $title, string $body ): string {
		$summary = '' !== trim( $title ) ? trim( $title ) : trim( strtok( $body, "\n" ) );
		if ( '' === $summary ) {
			$summary = __( 'Release published on GitHub.', 'jardin-theme' );
		}
		$summary = wp_strip_all_tags( $summary );
		if ( function_exists( 'mb_substr' ) ) {
			return mb_substr( $summary, 0, 180 );
		}
		return substr( $summary, 0, 180 );
	}
}
