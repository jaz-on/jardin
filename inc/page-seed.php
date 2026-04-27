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
 * Option: post ID of the wp_navigation post built by the seed tool (header block ref).
 */
const JARDIN_SEED_NAVIGATION_OPTION = 'jardin_seed_navigation_post_id';

/**
 * Write post meta without invoking `update_metadata` / `updated_post_meta`.
 *
 * Polylang (meta sync to translations) combined with plugins that assume a non-null
 * `WP_Post` in those hooks can fatally error. Direct write + cache flush matches
 * storage while skipping that chain. Note: Polylang will not auto-copy this meta
 * to translations from this write alone — run import per language or set templates
 * on linked pages if needed.
 *
 * @param int                $post_id    Post ID.
 * @param string             $meta_key   Meta key.
 * @param string|int|float|bool $meta_value Scalar value (stored like core meta).
 */
function jardin_page_seed_write_post_meta_silent( int $post_id, string $meta_key, $meta_value ): void {
	if ( $post_id < 1 || '' === $meta_key ) {
		return;
	}
	global $wpdb;
	$meta_value_db = maybe_serialize( wp_slash( $meta_value ) );
	$mid           = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT meta_id FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s LIMIT 1",
			$post_id,
			$meta_key
		)
	);
	if ( $mid > 0 ) {
		$wpdb->update(
			$wpdb->postmeta,
			array( 'meta_value' => $meta_value_db ),
			array(
				'post_id'  => $post_id,
				'meta_key' => $meta_key,
			),
			array( '%s' ),
			array( '%d', '%s' )
		);
	} else {
		$wpdb->insert(
			$wpdb->postmeta,
			array(
				'post_id'    => $post_id,
				'meta_key'   => $meta_key,
				'meta_value' => $meta_value_db,
			),
			array( '%d', '%s', '%s' )
		);
	}
	wp_cache_delete( $post_id, 'post_meta' );
	clean_post_cache( $post_id );
}

/**
 * While seeding: Polylang must not bulk-copy metas on `save_post` (`PLL_Sync_Metas::copy`).
 * That path calls `update_metadata` on translations and triggers MediaPapa Pro with a null post.
 *
 * @param list<string> $keys   Meta keys Polylang gathered to sync.
 * @param bool         $sync   True when synchronizing.
 * @param int          $from   Source post ID.
 * @param int          $to     Target post ID.
 * @param string       $lang   Target language slug.
 * @return list<string>
 */
function jardin_page_seed_pll_copy_post_metas_empty( $keys, $sync = false, $from = 0, $to = 0, $lang = '' ) {
	return array();
}

/**
 * Begin suppressing Polylang post meta sync (supports nested seed runs).
 */
function jardin_page_seed_pll_suppress_meta_sync_enter(): void {
	global $jardin_page_seed_pll_meta_suspend;
	$jardin_page_seed_pll_meta_suspend = (int) ( $jardin_page_seed_pll_meta_suspend ?? 0 ) + 1;
	if ( 1 === $jardin_page_seed_pll_meta_suspend ) {
		add_filter( 'pll_copy_post_metas', 'jardin_page_seed_pll_copy_post_metas_empty', PHP_INT_MAX, 5 );
	}
}

/**
 * End suppressing Polylang post meta sync.
 */
function jardin_page_seed_pll_suppress_meta_sync_leave(): void {
	global $jardin_page_seed_pll_meta_suspend;
	if ( empty( $jardin_page_seed_pll_meta_suspend ) ) {
		return;
	}
	$jardin_page_seed_pll_meta_suspend = ( (int) $jardin_page_seed_pll_meta_suspend ) - 1;
	if ( 0 === (int) $jardin_page_seed_pll_meta_suspend ) {
		remove_filter( 'pll_copy_post_metas', 'jardin_page_seed_pll_copy_post_metas_empty', PHP_INT_MAX, 5 );
	}
}

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
	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return;
	}
	jardin_page_seed_write_post_meta_silent( $post_id, '_wp_page_template', $template_slug );
}

/**
 * Create or update manifest pages.
 *
 * @param array<string, bool> $args {
 *     @type bool $sync_content        Overwrite post_content from seed files for existing pages.
 *     @type bool $assign_templates    Set _wp_page_template for every matched page (default true).
 *     @type bool $set_reading_home    If a page with slug `home` exists after run, set as static front page.
 *     @type bool $set_reading_posts   If Reading is already static, set posts page to slug `blog` when it exists.
 *     @type bool $create_navigation   Build wp_navigation `jardin-dev-nav` and link header Navigation block via option.
 * }
 * @return array<string, int> Slug => page ID.
 */
function jardin_page_seed_run( array $args = array() ): array {
	jardin_page_seed_pll_suppress_meta_sync_enter();
	try {
		return jardin_page_seed_run_inner( $args );
	} finally {
		jardin_page_seed_pll_suppress_meta_sync_leave();
	}
}

/**
 * Core manifest import (Polylang meta bulk sync is suspended by {@see jardin_page_seed_run()}).
 *
 * @param array<string, bool> $args Import options.
 * @return array<string, int> Slug => page ID.
 */
function jardin_page_seed_run_inner( array $args = array() ): array {
	$args = wp_parse_args(
		$args,
		array(
			'sync_content'       => false,
			'assign_templates'   => true,
			'set_reading_home'   => false,
			'set_reading_posts'  => false,
			'create_navigation'  => false,
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
			jardin_page_seed_write_post_meta_silent( $new_id, JARDIN_SEED_CREATED_META, '1' );
			if ( (bool) $args['assign_templates'] ) {
				jardin_page_seed_set_page_template( $new_id, $row['template'] );
			}
		}
	}

	if ( ! empty( $args['set_reading_home'] ) ) {
		jardin_page_seed_ensure_home_front( $ids_by_slug );
	}
	if ( ! empty( $args['set_reading_posts'] ) ) {
		jardin_page_seed_ensure_reading_posts_page( $ids_by_slug );
	}
	if ( ! empty( $args['create_navigation'] ) ) {
		jardin_page_seed_upsert_wp_navigation( $ids_by_slug );
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
 * Set the posts page to the published page with slug `blog` when Reading is already static.
 *
 * @param array<string, int> $ids_by_slug Slug => page ID from last import.
 */
function jardin_page_seed_ensure_reading_posts_page( array $ids_by_slug ): void {
	if ( 'page' !== (string) get_option( 'show_on_front' ) ) {
		return;
	}
	$blog_id = 0;
	if ( ! empty( $ids_by_slug['blog'] ) ) {
		$blog_id = (int) $ids_by_slug['blog'];
	}
	if ( $blog_id < 1 ) {
		$by_path = get_page_by_path( 'blog', OBJECT, 'page' );
		if ( $by_path instanceof WP_Post ) {
			$blog_id = (int) $by_path->ID;
		}
	}
	if ( $blog_id < 1 ) {
		return;
	}
	$post = get_post( $blog_id );
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type || 'publish' !== $post->post_status ) {
		return;
	}
	$front_id = (int) get_option( 'page_on_front', 0 );
	if ( $front_id === $blog_id ) {
		return;
	}
	update_option( 'page_for_posts', $blog_id );
}

/**
 * Resolve a manifest page ID after import (ids map or path lookup).
 *
 * @param string               $slug        Page slug.
 * @param string               $parent_slug Parent slug or empty.
 * @param array<string, int>   $ids_by_slug Slug => ID from current run.
 */
function jardin_page_seed_resolve_page_id( string $slug, string $parent_slug, array $ids_by_slug ): int {
	if ( ! empty( $ids_by_slug[ $slug ] ) ) {
		return (int) $ids_by_slug[ $slug ];
	}
	$path = jardin_page_seed_page_path( $slug, $parent_slug );
	$post = get_page_by_path( $path, OBJECT, 'page' );
	return $post instanceof WP_Post ? (int) $post->ID : 0;
}

/**
 * Ordered page IDs for the dev navigation: home, manifest pages, blog.
 *
 * @param array<string, int> $ids_by_slug Slug => page ID from last import.
 * @return list<int>
 */
function jardin_page_seed_collect_nav_page_ids( array $ids_by_slug ): array {
	$out   = array();
	$seen  = array();
	$push  = static function ( int $pid ) use ( &$out, &$seen ): void {
		if ( $pid < 1 || isset( $seen[ $pid ] ) ) {
			return;
		}
		$seen[ $pid ] = true;
		$out[]        = $pid;
	};
	$home = jardin_page_seed_resolve_page_id( 'home', '', $ids_by_slug );
	if ( $home > 0 ) {
		$push( $home );
	}
	foreach ( jardin_page_seed_get_manifest() as $row ) {
		if ( in_array( $row['slug'], array( 'home', 'blog' ), true ) ) {
			continue;
		}
		$pid = jardin_page_seed_resolve_page_id( $row['slug'], $row['parent_slug'], $ids_by_slug );
		if ( $pid > 0 ) {
			$push( $pid );
		}
	}
	$blog = jardin_page_seed_resolve_page_id( 'blog', '', $ids_by_slug );
	if ( $blog > 0 ) {
		$push( $blog );
	}
	return $out;
}

/**
 * Create or update a wp_navigation post and store its ID for the header block filter.
 *
 * @param array<string, int> $ids_by_slug Slug => page ID from last import.
 */
function jardin_page_seed_upsert_wp_navigation( array $ids_by_slug ): void {
	$page_ids = jardin_page_seed_collect_nav_page_ids( $ids_by_slug );
	$blocks   = array();
	foreach ( $page_ids as $pid ) {
		$post = get_post( $pid );
		if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
			continue;
		}
		$label = html_entity_decode( wp_strip_all_tags( get_the_title( $post ) ), ENT_QUOTES, 'UTF-8' );
		$url   = get_permalink( $post );
		if ( ! is_string( $url ) || '' === $url ) {
			continue;
		}
		$blocks[] = array(
			'blockName'    => 'core/navigation-link',
			'attrs'        => array(
				'label' => $label,
				'type'  => 'page',
				'id'    => (int) $pid,
				'url'   => $url,
				'kind'  => 'post-type',
			),
			'innerBlocks'  => array(),
			'innerHTML'    => '',
			'innerContent' => array(),
		);
	}
	if ( empty( $blocks ) || ! function_exists( 'serialize_blocks' ) ) {
		delete_option( JARDIN_SEED_NAVIGATION_OPTION );
		return;
	}
	$content = serialize_blocks( $blocks );

	$existing = get_posts(
		array(
			'name'             => 'jardin-dev-nav',
			'post_type'        => 'wp_navigation',
			'post_status'      => 'any',
			'posts_per_page'   => 1,
			'suppress_filters' => true,
			'fields'           => 'ids',
		)
	);
	$nav_id = ! empty( $existing[0] ) ? (int) $existing[0] : 0;

	$postarr = array(
		'post_title'   => __( 'Jardin dev menu', 'jardin' ),
		'post_name'    => 'jardin-dev-nav',
		'post_type'    => 'wp_navigation',
		'post_status'  => 'publish',
		'post_content' => wp_slash( $content ),
	);
	if ( $nav_id > 0 ) {
		$postarr['ID'] = $nav_id;
		$r             = wp_update_post( $postarr, true );
	} else {
		$r = wp_insert_post( $postarr, true );
	}
	if ( is_wp_error( $r ) || ! $r ) {
		delete_option( JARDIN_SEED_NAVIGATION_OPTION );
		return;
	}
	update_option( JARDIN_SEED_NAVIGATION_OPTION, (int) $r );
}

/**
 * Inject wp_navigation ref into the theme header Navigation block when it has no ref yet.
 *
 * @param array<string, mixed>      $parsed_block Parsed block.
 * @param array<string, mixed>|null $source_block Source (unused).
 * @param mixed                     $parent_block Parent (unused).
 * @return array<string, mixed>
 */
function jardin_page_seed_inject_navigation_ref( $parsed_block, $source_block = null, $parent_block = null ) {
	if ( ! is_array( $parsed_block ) || empty( $parsed_block['blockName'] ) || 'core/navigation' !== $parsed_block['blockName'] ) {
		return $parsed_block;
	}
	if ( ! empty( $parsed_block['attrs']['ref'] ) ) {
		return $parsed_block;
	}
	$class = isset( $parsed_block['attrs']['className'] ) ? (string) $parsed_block['attrs']['className'] : '';
	if ( ! str_contains( $class, 'jardin-primary-nav' ) ) {
		return $parsed_block;
	}
	$ref = (int) get_option( JARDIN_SEED_NAVIGATION_OPTION, 0 );
	if ( $ref < 1 ) {
		return $parsed_block;
	}
	$nav = get_post( $ref );
	if ( ! $nav instanceof WP_Post || 'wp_navigation' !== $nav->post_type ) {
		return $parsed_block;
	}
	$parsed_block['attrs']['ref'] = $ref;
	return $parsed_block;
}

add_filter( 'render_block_data', 'jardin_page_seed_inject_navigation_ref', 10, 3 );

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
	jardin_page_seed_pll_suppress_meta_sync_enter();
	try {
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
	} finally {
		jardin_page_seed_pll_suppress_meta_sync_leave();
	}
}

/**
 * Trash or delete pages created by this tool (meta _jardin_seed_created).
 *
 * @param bool $force True = skip trash.
 * @return int Count removed.
 */
function jardin_page_seed_clean_tagged_only( bool $force = false ): int {
	jardin_page_seed_pll_suppress_meta_sync_enter();
	try {
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
			)
		);
		$removed = 0;
		foreach ( $query->posts as $post_id ) {
			wp_delete_post( (int) $post_id, $force );
			++$removed;
		}
		return $removed;
	} finally {
		jardin_page_seed_pll_suppress_meta_sync_leave();
	}
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
	jardin_page_seed_pll_suppress_meta_sync_enter();
	try {
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
	} finally {
		jardin_page_seed_pll_suppress_meta_sync_leave();
	}
}
