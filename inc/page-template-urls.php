<?php
/**
 * Resolve published page permalinks from FSE template assignment (_wp_page_template).
 *
 * Avoids hard-coded hub paths: URLs follow the page slug chosen in the editor
 * as long as the page uses the expected custom template (e.g. page-journal).
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Possible _wp_page_template meta values for a theme template basename.
 *
 * @param string $basename e.g. page-journal (with or without .html).
 * @return list<string>
 */
function jardin_page_template_meta_variants( string $basename ): array {
	$basename = trim( $basename );
	$basename = preg_replace( '#^templates/#', '', $basename );
	$basename = preg_replace( '/\.html$/', '', $basename );
	$base     = sanitize_file_name( $basename );
	if ( '' === $base ) {
		return array();
	}
	$html = $base . '.html';
	return array_unique(
		array(
			$html,
			'templates/' . $html,
		)
	);
}

/**
 * @param string $template_basename e.g. page-journal
 * @return int Post ID or 0
 */
function jardin_get_page_id_for_theme_template( string $template_basename ): int {
	static $cache = array();

	$lang_key = 'default';
	if ( function_exists( 'pll_current_language' ) ) {
		$slug = (string) pll_current_language( 'slug' );
		if ( '' !== $slug ) {
			$lang_key = $slug;
		}
	}

	$cache_key = $template_basename . '|' . $lang_key;
	if ( array_key_exists( $cache_key, $cache ) ) {
		return $cache[ $cache_key ];
	}

	$variants = jardin_page_template_meta_variants( $template_basename );
	if ( empty( $variants ) ) {
		$cache[ $cache_key ] = 0;
		return 0;
	}

	$q = new WP_Query(
		array(
			'post_type'              => 'page',
			'post_status'            => 'publish',
			'posts_per_page'         => 3,
			'orderby'                => 'menu_order',
			'order'                  => 'ASC',
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_query'             => array(
				array(
					'key'     => '_wp_page_template',
					'value'   => $variants,
					'compare' => 'IN',
				),
			),
		)
	);

	$page_id = 0;
	if ( $q->have_posts() ) {
		if ( $q->post_count > 1 && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			_doing_it_wrong(
				__FUNCTION__,
				sprintf(
					/* translators: %s: theme template basename */
					__( 'Multiple published pages use template %s; using the first match.', 'jardin-theme' ),
					$template_basename
				),
				'6.8.0'
			);
		}
		$raw_id = (int) $q->posts[0]->ID;
		if ( $raw_id > 0 && function_exists( 'pll_get_post' ) && function_exists( 'pll_current_language' ) ) {
			$lang = (string) pll_current_language( 'slug' );
			if ( '' !== $lang ) {
				$translated = (int) pll_get_post( $raw_id, $lang );
				if ( $translated > 0 ) {
					$page_id = $translated;
				} else {
					$page_id = $raw_id;
				}
			} else {
				$page_id = $raw_id;
			}
		} else {
			$page_id = $raw_id;
		}
	}

	wp_reset_postdata();

	$cache[ $cache_key ] = $page_id;
	return $page_id;
}

/**
 * @param string $template_basename e.g. page-journal
 */
function jardin_get_page_post_for_theme_template( string $template_basename ): ?WP_Post {
	$id = jardin_get_page_id_for_theme_template( $template_basename );
	if ( $id <= 0 ) {
		return null;
	}
	$post = get_post( $id );
	return $post instanceof WP_Post ? $post : null;
}

/**
 * Permalink for the page assigned this theme template, or empty string.
 *
 * @param string $template_basename e.g. page-journal
 */
function jardin_get_page_url_for_theme_template( string $template_basename ): string {
	$post = jardin_get_page_post_for_theme_template( $template_basename );
	if ( ! $post instanceof WP_Post ) {
		return '';
	}
	$url = get_permalink( $post );
	return is_string( $url ) ? $url : '';
}

/**
 * Path label for nav (leading slash, no trailing slash), from a full URL.
 *
 * @param string $url Full URL.
 */
function jardin_get_path_label_for_url( string $url ): string {
	$url = trim( $url );
	if ( '' === $url ) {
		return '';
	}
	$path = (string) wp_parse_url( $url, PHP_URL_PATH );
	$path = '/' === $path ? '' : untrailingslashit( $path );
	return $path;
}

/**
 * Hub URL from template, or legacy path filtered for backwards compatibility.
 *
 * @param string $template_basename e.g. page-journal
 * @param string $legacy_path       Leading slash, e.g. /journal/
 */
function jardin_get_hub_url_from_template_or_legacy( string $template_basename, string $legacy_path ): string {
	$url = jardin_get_page_url_for_theme_template( $template_basename );
	if ( '' !== $url ) {
		return trailingslashit( $url );
	}
	$path = (string) apply_filters( 'jardin_hub_legacy_path', $legacy_path, $template_basename );
	$path = '/' . trim( $path, '/' ) . '/';
	return trailingslashit( home_url( $path ) );
}

