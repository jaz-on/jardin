<?php
/**
 * Dev helpers: create/update manifest pages, assign FSE templates, optional seed HTML.
 *
 * Inspired by wpis-theme (manifest + Appearance screen + WP-CLI). Default import does
 * not overwrite existing post_content so production-cloned pages stay intact; use sync
 * only when you intentionally replace body from theme seed files.
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Meta key set on pages created by this tool (safe clean targets them only).
 */
const JARDIN_SEED_CREATED_META = '_jardin_seed_created';

/**
 * Read optional seed HTML from content/seeds/{filename}.
 *
 * @param string $filename Basename, e.g. journal.html.
 * @return string File contents or empty string.
 */
function jardin_page_seed_get_seed_html( string $filename ): string {
	$path = get_template_directory() . '/content/seeds/' . ltrim( $filename, '/' );
	if ( ! is_readable( $path ) ) {
		return '';
	}
	$raw = file_get_contents( $path );
	return is_string( $raw ) ? $raw : '';
}

/**
 * Manifest: slug, title, parent slug, FSE template slug (theme.json customTemplates name), seed file.
 *
 * @return list<array{slug:string,title:string,parent_slug:string,template:string,file:string}>
 */
function jardin_page_seed_get_manifest(): array {
	return array(
		array(
			'slug'        => 'journal',
			'title'       => 'Journal',
			'parent_slug' => '',
			'template'    => 'page-journal',
			'file'        => 'journal.html',
		),
		array(
			'slug'        => 'maintenant',
			'title'       => 'Maintenant',
			'parent_slug' => '',
			'template'    => 'page-maintenant',
			'file'        => 'maintenant.html',
		),
		array(
			'slug'        => 'bieres',
			'title'       => 'Bières',
			'parent_slug' => '',
			'template'    => 'page-bieres',
			'file'        => 'bieres.html',
		),
		array(
			'slug'        => 'blogroll',
			'title'       => 'Blogroll',
			'parent_slug' => '',
			'template'    => 'page-blogroll',
			'file'        => 'blogroll.html',
		),
		array(
			'slug'        => 'styleguide',
			'title'       => 'Styleguide',
			'parent_slug' => '',
			'template'    => 'page-styleguide',
			'file'        => 'styleguide.html',
		),
		array(
			'slug'        => 'colophon',
			'title'       => 'Colophon',
			'parent_slug' => '',
			'template'    => 'page-colophon',
			'file'        => 'colophon.html',
		),
		array(
			'slug'        => 'coffee',
			'title'       => 'Coffee',
			'parent_slug' => '',
			'template'    => 'page-coffee',
			'file'        => 'coffee.html',
		),
		array(
			'slug'        => 'dlc',
			'title'       => 'DLC',
			'parent_slug' => '',
			'template'    => 'page-dlc',
			'file'        => 'dlc.html',
		),
		array(
			'slug'        => 'licence',
			'title'       => 'Licence',
			'parent_slug' => '',
			'template'    => 'page-licence',
			'file'        => 'licence.html',
		),
		array(
			'slug'        => 'privacy',
			'title'       => 'Privacy',
			'parent_slug' => '',
			'template'    => 'page-privacy',
			'file'        => 'privacy.html',
		),
		array(
			'slug'        => 'ai',
			'title'       => 'AI',
			'parent_slug' => '',
			'template'    => 'page-ai',
			'file'        => 'ai.html',
		),
		array(
			'slug'        => 'changelog',
			'title'       => 'Changelog',
			'parent_slug' => '',
			'template'    => 'page-changelog',
			'file'        => 'changelog.html',
		),
		array(
			'slug'        => 'uses',
			'title'       => 'Uses',
			'parent_slug' => '',
			'template'    => 'page-uses',
			'file'        => 'uses.html',
		),
	);
}

/**
 * Hierarchical path for get_page_by_path().
 *
 * @param string $slug        Page slug.
 * @param string $parent_slug Parent slug or empty.
 */
function jardin_page_seed_page_path( string $slug, string $parent_slug ): string {
	if ( '' === $parent_slug ) {
		return $slug;
	}
	return $parent_slug . '/' . $slug;
}

/**
 * Wrap raw HTML as core/html block when file is not block-first.
 *
 * @param string $inner Raw HTML.
 */
function jardin_page_seed_html_block_content( string $inner ): string {
	$inner = str_replace( ']]>', ']]]]><![CDATA[>', $inner );
	if ( function_exists( 'serialize_block' ) ) {
		return serialize_block(
			array(
				'blockName'    => 'core/html',
				'attrs'        => array(),
				'innerBlocks'  => array(),
				'innerHTML'    => $inner,
				'innerContent' => array( $inner ),
			)
		);
	}
	return "<!-- wp:html -->\n" . $inner . "\n<!-- /wp:html -->";
}

/**
 * Build post_content from seed file contents.
 *
 * @param string $raw File contents.
 */
function jardin_page_seed_build_post_content( string $raw ): string {
	$raw  = is_string( $raw ) ? $raw : '';
	$raw  = str_replace( ']]>', ']]]]><![CDATA[>', $raw );
	$trim = trim( $raw );
	if ( '' !== $trim && str_starts_with( $trim, '<!-- wp:' ) ) {
		return $raw;
	}
	if ( '' === $trim ) {
		return "<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->";
	}
	return jardin_page_seed_html_block_content( $raw );
}

/**
 * Assign FSE custom page template.
 *
 * @param int    $post_id        Page ID.
 * @param string $template_slug Template name from theme.json (e.g. page-journal).
 */
function jardin_page_seed_set_page_template( int $post_id, string $template_slug ): void {
	if ( $post_id < 1 || '' === $template_slug ) {
		return;
	}
	update_post_meta( $post_id, '_wp_page_template', $template_slug );
}

/**
 * Create or update manifest pages.
 *
 * @param array<string, bool> $args {
 *     @type bool $sync_content       Overwrite post_content from seed files for existing pages.
 *     @type bool $assign_templates   Set _wp_page_template for every matched page (default true).
 *     @type bool $set_reading_home   If a page with slug `home` exists after run, set as static front page.
 * }
 * @return array<string, int> Slug => page ID.
 */
function jardin_page_seed_run( array $args = array() ): array {
	$args = wp_parse_args(
		$args,
		array(
			'sync_content'     => false,
			'assign_templates' => true,
			'set_reading_home' => false,
		)
	);

	$ids_by_slug = array();

	foreach ( jardin_page_seed_get_manifest() as $row ) {
		$path     = jardin_page_seed_page_path( $row['slug'], $row['parent_slug'] );
		$existing = get_page_by_path( $path, OBJECT, 'page' );

		$parent_id = 0;
		if ( '' !== $row['parent_slug'] ) {
			$parent = get_page_by_path( $row['parent_slug'], OBJECT, 'page' );
			if ( $parent instanceof WP_Post ) {
				$parent_id = (int) $parent->ID;
			}
		}

		$inner   = jardin_page_seed_get_seed_html( $row['file'] );
		$content = jardin_page_seed_build_post_content( $inner );

		if ( $existing instanceof WP_Post ) {
			$pid = (int) $existing->ID;
			$ids_by_slug[ $row['slug'] ] = $pid;

			$updates = array( 'ID' => $pid );

			if ( (bool) $args['sync_content'] ) {
				$updates['post_content'] = $content;
			}
			if ( $parent_id !== (int) $existing->post_parent ) {
				$updates['post_parent'] = $parent_id;
			}
			if ( count( $updates ) > 1 ) {
				wp_update_post( $updates );
			}

			if ( (bool) $args['assign_templates'] ) {
				jardin_page_seed_set_page_template( $pid, $row['template'] );
			}
			continue;
		}

		$page_author = ( get_current_user_id() > 0 ) ? (int) get_current_user_id() : 0;
		if ( $page_author < 1 && get_userdata( 1 ) ) {
			$page_author = 1;
		}

		$new_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $row['title'],
				'post_name'    => $row['slug'],
				'post_parent'  => $parent_id,
				'post_content' => $content,
				'post_author'  => $page_author,
			),
			true
		);

		if ( ! is_wp_error( $new_id ) && $new_id > 0 ) {
			$new_id = (int) $new_id;
			$ids_by_slug[ $row['slug'] ] = $new_id;
			update_post_meta( $new_id, JARDIN_SEED_CREATED_META, '1' );
			if ( (bool) $args['assign_templates'] ) {
				jardin_page_seed_set_page_template( $new_id, $row['template'] );
			}
		}
	}

	if ( ! empty( $args['set_reading_home'] ) ) {
		jardin_page_seed_ensure_home_front( $ids_by_slug );
	}

	return $ids_by_slug;
}

/**
 * Set static front page to the `home` slug if that page exists and is published.
 *
 * @param array<string, int> $ids_by_slug Slug => page ID from last import.
 */
function jardin_page_seed_ensure_home_front( array $ids_by_slug ): void {
	$home_id = 0;
	if ( ! empty( $ids_by_slug['home'] ) ) {
		$home_id = (int) $ids_by_slug['home'];
	}
	if ( $home_id < 1 ) {
		$by_path = get_page_by_path( 'home', OBJECT, 'page' );
		if ( $by_path instanceof WP_Post ) {
			$home_id = (int) $by_path->ID;
		}
	}
	if ( $home_id < 1 ) {
		return;
	}
	$post = get_post( $home_id );
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type || 'publish' !== $post->post_status ) {
		return;
	}
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_id );
}

/**
 * Paths deepest-first for hierarchical deletes.
 *
 * @return list<string>
 */
function jardin_page_seed_manifest_paths_deepest_first(): array {
	$paths = array();
	foreach ( jardin_page_seed_get_manifest() as $row ) {
		$paths[] = jardin_page_seed_page_path( $row['slug'], $row['parent_slug'] );
	}
	usort(
		$paths,
		static function ( string $a, string $b ): int {
			return substr_count( $b, '/' ) <=> substr_count( $a, '/' );
		}
	);
	return $paths;
}

/**
 * Trash or delete pages that match manifest paths (destructive).
 *
 * @param bool $force True = skip trash.
 * @return int Count removed.
 */
function jardin_page_seed_clean_manifest_slugs( bool $force = false ): int {
	$removed = 0;
	foreach ( jardin_page_seed_manifest_paths_deepest_first() as $path ) {
		$post = get_page_by_path( $path, OBJECT, 'page' );
		if ( ! $post instanceof WP_Post ) {
			continue;
		}
		wp_delete_post( (int) $post->ID, $force );
		++$removed;
	}
	return $removed;
}

/**
 * Trash or delete pages created by this tool (meta _jardin_seed_created).
 *
 * @param bool $force True = skip trash.
 * @return int Count removed.
 */
function jardin_page_seed_clean_tagged_only( bool $force = false ): int {
	$query = new WP_Query(
		array(
			'post_type'              => 'page',
			'post_status'            => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page'         => -1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_key'               => JARDIN_SEED_CREATED_META,
			'meta_value'             => '1',
			'update_post_term_cache' => false,
		)
	);
	$removed = 0;
	foreach ( $query->posts as $post_id ) {
		wp_delete_post( (int) $post_id, $force );
		++$removed;
	}
	return $removed;
}

/**
 * Reset reading if front page is gone.
 */
function jardin_page_seed_reset_reading_after_clean(): void {
	$front_id = (int) get_option( 'page_on_front', 0 );
	if ( $front_id <= 0 ) {
		return;
	}
	$post = get_post( $front_id );
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type || in_array( $post->post_status, array( 'trash', 'draft', 'pending' ), true ) ) {
		update_option( 'show_on_front', 'posts' );
		update_option( 'page_on_front', 0 );
	}
}

/**
 * Count Site Editor DB overrides.
 *
 * @param string $post_type wp_template or wp_template_part.
 */
function jardin_page_seed_count_fse_posts( string $post_type ): int {
	if ( ! in_array( $post_type, array( 'wp_template', 'wp_template_part' ), true ) ) {
		return 0;
	}
	$query = new WP_Query(
		array(
			'post_type'              => $post_type,
			'post_status'            => array( 'publish', 'draft', 'auto-draft', 'trash' ),
			'posts_per_page'         => -1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);
	return count( $query->posts );
}

/**
 * Delete all wp_template and wp_template_part posts (restore theme files from disk).
 *
 * @return int Number of posts deleted.
 */
function jardin_page_seed_reset_fse_customizations(): int {
	$removed = 0;
	foreach ( array( 'wp_template_part', 'wp_template' ) as $post_type ) {
		$query = new WP_Query(
			array(
				'post_type'              => $post_type,
				'post_status'            => array( 'publish', 'draft', 'auto-draft', 'trash' ),
				'posts_per_page'         => -1,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_term_meta_cache' => false,
			)
		);
		foreach ( $query->posts as $post_id ) {
			if ( wp_delete_post( (int) $post_id, true ) ) {
				++$removed;
			}
		}
	}
	if ( function_exists( 'wp_cache_flush_group' ) ) {
		wp_cache_flush_group( 'theme_json' );
		wp_cache_flush_group( 'theme_files' );
	}
	if ( class_exists( 'WP_Theme_JSON_Resolver' ) && method_exists( 'WP_Theme_JSON_Resolver', 'clean_cached_data' ) ) {
		WP_Theme_JSON_Resolver::clean_cached_data();
	}
	return $removed;
}
