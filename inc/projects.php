<?php
/**
 * Project CPT, taxonomy, meta, GitHub sync hooks (theme-owned, same spirit as now-updates).
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * CSS class on Query Loop blocks that pin featured projects on the home pattern.
 */
const JARDIN_THEME_PROJECT_QUERY_FEATURED = 'jardin-projects-query--featured';

/**
 * Sync singleton (registers cron listener).
 *
 * @return Jardin_Projects_Sync
 */
function jardin_projects_sync_service(): Jardin_Projects_Sync {
	static $sync = null;
	if ( null === $sync ) {
		$sync = new Jardin_Projects_Sync();
	}
	return $sync;
}

/**
 * Register project custom post type.
 */
function jardin_register_project_cpt(): void {
	$labels = array(
		'name'               => __( 'Projects', 'jardin-theme' ),
		'singular_name'      => __( 'Project', 'jardin-theme' ),
		'add_new'            => __( 'Add project', 'jardin-theme' ),
		'add_new_item'       => __( 'Add new project', 'jardin-theme' ),
		'edit_item'          => __( 'Edit project', 'jardin-theme' ),
		'new_item'           => __( 'New project', 'jardin-theme' ),
		'view_item'          => __( 'View project', 'jardin-theme' ),
		'search_items'       => __( 'Search projects', 'jardin-theme' ),
		'not_found'          => __( 'No projects found', 'jardin-theme' ),
		'not_found_in_trash' => __( 'No projects found in Trash', 'jardin-theme' ),
		'all_items'          => __( 'All projects', 'jardin-theme' ),
		'archives'           => __( 'Project archives', 'jardin-theme' ),
		'menu_name'          => __( 'Projects', 'jardin-theme' ),
	);

	$args = array(
		'label'               => __( 'Projects', 'jardin-theme' ),
		'labels'              => $labels,
		'public'              => true,
		'show_in_rest'        => true,
		'has_archive'         => 'projets',
		'rewrite'             => array(
			'slug' => 'projets',
		),
		'supports'            => array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'page-attributes',
			'custom-fields',
		),
		'menu_icon'           => 'dashicons-portfolio',
		'show_in_menu'        => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'show_in_admin_bar'   => true,
	);

	$args = apply_filters( 'jardin_projects_register_post_type_args', $args );

	register_post_type( jardin_projects_get_post_type(), $args );
}
add_action( 'init', 'jardin_register_project_cpt', 5 );

/**
 * Register project status taxonomy.
 */
function jardin_register_project_status_taxonomy(): void {
	$labels = array(
		'name'          => __( 'Project statuses', 'jardin-theme' ),
		'singular_name' => __( 'Project status', 'jardin-theme' ),
		'menu_name'     => __( 'Statuses', 'jardin-theme' ),
		'all_items'     => __( 'All statuses', 'jardin-theme' ),
		'edit_item'     => __( 'Edit status', 'jardin-theme' ),
		'update_item'   => __( 'Update status', 'jardin-theme' ),
		'add_new_item'  => __( 'Add status', 'jardin-theme' ),
	);

	register_taxonomy(
		jardin_projects_get_status_taxonomy(),
		array( jardin_projects_get_post_type() ),
		array(
			'labels'            => $labels,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'hierarchical'      => false,
			'rewrite'           => false,
			'query_var'         => true,
		)
	);
}
add_action( 'init', 'jardin_register_project_status_taxonomy', 5 );

/**
 * REST/meta registration for project fields.
 */
function jardin_register_project_meta(): void {
	$post_type = jardin_projects_get_post_type();
	$fields    = array(
		'current_version' => 'sanitize_text_field',
		'license'         => 'sanitize_text_field',
		'stack_label'     => 'sanitize_text_field',
		'repo_url'        => 'esc_url_raw',
		'wporg_url'       => 'esc_url_raw',
		'sync_mode'       => 'jardin_projects_sanitize_sync_mode',
	);

	foreach ( $fields as $key => $sanitize ) {
		register_post_meta(
			$post_type,
			$key,
			array(
				'single'            => true,
				'type'              => 'string',
				'show_in_rest'      => true,
				'sanitize_callback' => $sanitize,
				'auth_callback'     => 'jardin_project_meta_auth_callback',
			)
		);
	}

	register_post_meta(
		$post_type,
		'project_featured',
		array(
			'single'            => true,
			'type'              => 'boolean',
			'default'           => false,
			'show_in_rest'      => true,
			'sanitize_callback' => static function ( $value ) {
				return (bool) $value;
			},
			'auth_callback'     => 'jardin_project_meta_auth_callback',
		)
	);

	foreach (
		array(
			'_jardin_project_sync_state'    => 'sanitize_text_field',
			'_jardin_project_last_sync_at'  => 'sanitize_text_field',
			'_jardin_project_last_error'    => 'sanitize_text_field',
		) as $sync_key => $sync_sanitize
	) {
		register_post_meta(
			$post_type,
			$sync_key,
			array(
				'single'            => true,
				'type'              => 'string',
				'show_in_rest'      => true,
				'sanitize_callback' => $sync_sanitize,
				'auth_callback'     => 'jardin_project_meta_auth_callback',
			)
		);
	}
}
add_action( 'init', 'jardin_register_project_meta', 5 );

/**
 * @param bool   $allowed  Whether allowed.
 * @param string $meta_key Meta key.
 * @param int    $post_id  Post ID.
 * @param int    $user_id  User ID.
 * @param string $cap      Capability.
 * @param array  $caps     Caps.
 * @return bool
 */
function jardin_project_meta_auth_callback( $allowed, $meta_key, $post_id, $user_id, $cap, $caps ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	return current_user_can( 'edit_post', (int) $post_id );
}

/**
 * Seed default status terms.
 */
function jardin_seed_project_status_terms(): void {
	$taxonomy = jardin_projects_get_status_taxonomy();
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return;
	}

	$defaults = array(
		'actif'    => __( 'active', 'jardin-theme' ),
		'wip'      => __( 'in progress', 'jardin-theme' ),
		'stable'   => __( 'stable', 'jardin-theme' ),
		'planned'  => __( 'planned', 'jardin-theme' ),
		'archived' => __( 'archived', 'jardin-theme' ),
	);

	foreach ( $defaults as $slug => $label ) {
		if ( ! term_exists( $slug, $taxonomy ) ) {
			wp_insert_term( $label, $taxonomy, array( 'slug' => $slug ) );
		}
	}
}
add_action( 'init', 'jardin_seed_project_status_terms', 5 );

/**
 * Wire sync cron after CPT exists.
 */
function jardin_projects_bootstrap_sync(): void {
	jardin_projects_sync_service();
	Jardin_Projects_Sync::ensure_cron();
}
add_action( 'init', 'jardin_projects_bootstrap_sync', 6 );

/**
 * Post IDs for the home pinned-projects grid: featured first (menu_order, date), then fill with recently modified.
 *
 * @param int $limit Max items (home grid uses 3).
 * @return int[]
 */
function jardin_projects_get_home_featured_post_ids( int $limit = 3 ): array {
	$pt = jardin_projects_get_post_type();
	if ( ! post_type_exists( $pt ) || $limit < 1 ) {
		return array();
	}

	$featured = get_posts(
		array(
			'post_type'           => $pt,
			'post_status'         => 'publish',
			'posts_per_page'      => $limit,
			'orderby'             => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
			'fields'              => 'ids',
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
			'meta_query'          => array(
				array(
					'key'     => 'project_featured',
					'value'   => '1',
					'compare' => '=',
				),
			),
		)
	);

	$featured = array_map( 'intval', $featured );

	if ( count( $featured ) >= $limit ) {
		return array_slice( $featured, 0, $limit );
	}

	$need = $limit - count( $featured );
	$fill = get_posts(
		array(
			'post_type'           => $pt,
			'post_status'         => 'publish',
			'posts_per_page'      => $need,
			'orderby'             => 'modified',
			'order'               => 'DESC',
			'post__not_in'        => $featured,
			'fields'              => 'ids',
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		)
	);

	return array_merge( $featured, array_map( 'intval', $fill ) );
}

/**
 * Featured Query Loop on home: pinned meta + fallback to latest modified (mockup parity).
 *
 * @param array     $query Query vars.
 * @param \WP_Block $block Block instance.
 * @param int       $page  Current page.
 * @return array
 */
function jardin_projects_filter_featured_query_loop( $query, $block, $page ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	if ( ! post_type_exists( jardin_projects_get_post_type() ) ) {
		return $query;
	}

	if ( empty( $query['post_type'] ) ) {
		return $query;
	}

	$post_type        = $query['post_type'];
	$is_project_query = ( is_string( $post_type ) && jardin_projects_get_post_type() === $post_type )
		|| ( is_array( $post_type ) && 1 === count( $post_type ) && jardin_projects_get_post_type() === $post_type[0] );

	if ( ! $is_project_query ) {
		return $query;
	}

	$class = isset( $block->parsed_block['attrs']['className'] ) ? (string) $block->parsed_block['attrs']['className'] : '';
	if ( false === strpos( $class, JARDIN_THEME_PROJECT_QUERY_FEATURED ) ) {
		return $query;
	}

	$per_page = isset( $query['posts_per_page'] ) ? (int) $query['posts_per_page'] : 3;
	if ( $per_page < 1 ) {
		$per_page = 3;
	}

	$ids = jardin_projects_get_home_featured_post_ids( $per_page );

	if ( empty( $ids ) ) {
		$query['post__in'] = array( 0 );
	} else {
		$query['post__in'] = $ids;
	}
	$query['orderby'] = 'post__in';

	unset( $query['meta_key'], $query['meta_value'], $query['meta_compare'], $query['meta_query'] );

	return $query;
}
add_filter( 'query_loop_block_query_vars', 'jardin_projects_filter_featured_query_loop', 15, 3 );

/**
 * Sync published projects when auto mode is on.
 *
 * @param int      $post_id Post ID.
 * @param \WP_Post $post    Post.
 * @param bool     $update  Is update.
 */
function jardin_projects_maybe_sync_after_save( $post_id, $post, $update ): void {
	unset( $update );
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}
	if ( ! $post instanceof WP_Post || jardin_projects_get_post_type() !== $post->post_type ) {
		return;
	}
	if ( 'publish' !== $post->post_status || ! jardin_projects_is_auto_sync_enabled( (int) $post_id ) ) {
		return;
	}
	jardin_projects_sync_service()->sync_project( (int) $post_id, 'save_post' );
}
add_action( 'save_post_project', 'jardin_projects_maybe_sync_after_save', 20, 3 );

/**
 * Default taxonomy term when none set.
 *
 * @param int              $post_id     Post ID.
 * @param \WP_Post         $post        Post object.
 * @param bool             $update      Whether this is an update.
 * @param null|\WP_Post    $post_before Previous post state.
 */
function jardin_projects_ensure_default_status_term( $post_id, $post, $update, $post_before ): void {
	unset( $update, $post_before );

	if ( ! $post instanceof WP_Post || jardin_projects_get_post_type() !== $post->post_type ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}

	if ( ! in_array( $post->post_status, array( 'publish', 'future', 'draft', 'pending', 'private' ), true ) ) {
		return;
	}

	$terms = wp_get_post_terms( (int) $post_id, jardin_projects_get_status_taxonomy() );
	if ( is_wp_error( $terms ) ) {
		return;
	}
	if ( ! empty( $terms ) ) {
		return;
	}

	wp_set_object_terms( (int) $post_id, 'wip', jardin_projects_get_status_taxonomy(), false );
}
add_action( 'wp_after_insert_post', 'jardin_projects_ensure_default_status_term', 10, 4 );

/**
 * Flush rewrite rules when switching to this theme so /projets/ resolves.
 */
function jardin_projects_flush_rewrites_on_theme_switch(): void {
	flush_rewrite_rules( false );
}
add_action( 'after_switch_theme', 'jardin_projects_flush_rewrites_on_theme_switch' );

/**
 * Block editor: sidebar panels for project meta (no classic metabox).
 */
function jardin_enqueue_project_block_editor_assets(): void {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'project' !== (string) ( $screen->post_type ?? '' ) ) {
		return;
	}

	$rel = '/assets/js/project-editor-sidebar.js';
	$dir = get_template_directory();
	$uri = get_template_directory_uri();
	$ver = wp_get_theme()->get( 'Version' );

	$handle = 'jardin-theme-project-editor-sidebar';

	wp_enqueue_script(
		$handle,
		$uri . $rel,
		array(
			'wp-plugins',
			'wp-edit-post',
			'wp-editor',
			'wp-element',
			'wp-components',
			'wp-data',
			'wp-i18n',
			'wp-core-data',
		),
		file_exists( $dir . $rel ) ? (string) filemtime( $dir . $rel ) : $ver,
		true
	);

	$post_id  = isset( $_GET['post'] ) ? absint( wp_unslash( $_GET['post'] ) ) : 0;
	$sync_url = '';
	if ( $post_id > 0 && current_user_can( 'edit_post', $post_id ) ) {
		$sync_url = wp_nonce_url(
			add_query_arg(
				array(
					'action'     => 'jardin_projects_sync',
					'project_id' => $post_id,
				),
				admin_url( 'admin-post.php' )
			),
			'jardin_projects_sync_' . $post_id
		);
	}

	wp_localize_script(
		$handle,
		'jardinProjectEditor',
		array(
			'syncUrl'        => $sync_url,
			'defaultLicense' => jardin_projects_get_default_meta()['license'],
		)
	);

	wp_set_script_translations( $handle, 'jardin-theme', $dir . '/languages' );
}
add_action( 'enqueue_block_editor_assets', 'jardin_enqueue_project_block_editor_assets' );
