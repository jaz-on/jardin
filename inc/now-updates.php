<?php
/**
 * now_update CPT + migration helpers.
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register now_update custom post type.
 */
function jardin_register_now_update_cpt(): void {
	$labels = array(
		'name'               => _x( 'Now updates', 'post type general name', 'jardin-theme' ),
		'singular_name'      => _x( 'Now update', 'post type singular name', 'jardin-theme' ),
		'menu_name'          => _x( 'Now updates', 'admin menu', 'jardin-theme' ),
		'name_admin_bar'     => _x( 'Now update', 'add new on admin bar', 'jardin-theme' ),
		'add_new'            => _x( 'Add New', 'now_update', 'jardin-theme' ),
		'add_new_item'       => __( 'Add new now', 'jardin-theme' ),
		'new_item'           => __( 'New Now update', 'jardin-theme' ),
		'edit_item'          => __( 'Edit Now update', 'jardin-theme' ),
		'view_item'          => __( 'View Now update', 'jardin-theme' ),
		'all_items'          => __( 'All Now updates', 'jardin-theme' ),
		'search_items'       => __( 'Search Now updates', 'jardin-theme' ),
		'parent_item_colon'  => __( 'Parent Now updates:', 'jardin-theme' ),
		'not_found'          => __( 'No now updates found.', 'jardin-theme' ),
		'not_found_in_trash' => __( 'No now updates found in Trash.', 'jardin-theme' ),
	);

	register_post_type(
		'now_update',
		array(
			'labels'            => $labels,
			'public'            => true,
			'publicly_queryable'=> true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => false,
			'has_archive'       => 'now-updates',
			'menu_position'     => 22,
			'menu_icon'         => 'dashicons-calendar-alt',
			'supports'          => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author' ),
		)
	);
}
add_action( 'init', 'jardin_register_now_update_cpt', 5 );

/**
 * Build canonical URL path segment for now update singles.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function jardin_now_updates_path_for_post( int $post_id ): string {
	$base = 'now-updates';
	if ( function_exists( 'pll_get_post_language' ) ) {
		$lang = (string) pll_get_post_language( $post_id, 'slug' );
		if ( 'fr' === $lang ) {
			$base = 'now';
		}
	}
	/**
	 * Filter: jardin_now_updates_path
	 *
	 * @param string $base    Base path segment.
	 * @param int    $post_id Post ID.
	 */
	return (string) apply_filters( 'jardin_now_updates_path', $base, $post_id );
}

/**
 * Canonical permalink for now_update.
 *
 * @param string   $url  Permalink.
 * @param \WP_Post $post Post.
 * @return string
 */
function jardin_filter_now_update_post_type_link( $url, $post ) {
	if ( ! $post instanceof WP_Post || 'now_update' !== $post->post_type ) {
		return $url;
	}

	$slug = (string) $post->post_name;
	if ( '' === $slug ) {
		return $url;
	}

	$base = jardin_now_updates_path_for_post( (int) $post->ID );
	return home_url( user_trailingslashit( $base . '/' . $slug ) );
}
add_filter( 'post_type_link', 'jardin_filter_now_update_post_type_link', 20, 2 );

/**
 * Register WP-CLI command for posts -> now_update migration.
 */
function jardin_register_now_update_cli_command(): void {
	if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
		return;
	}

	WP_CLI::add_command( 'jardin now-updates-migrate', 'jardin_cli_now_updates_migrate' );
}
add_action( 'init', 'jardin_register_now_update_cli_command', 30 );

/**
 * Run migration from `post + category now-updates` to `now_update`.
 *
 * ## OPTIONS
 *
 * [--dry-run]
 * : Simulate without writing.
 *
 * [--limit=<n>]
 * : Limit number of source posts.
 *
 * [--keep-old-published]
 * : Keep source posts published (default behavior is demote to draft).
 *
 * @param array $args Positional args.
 * @param array $assoc Assoc args.
 */
function jardin_cli_now_updates_migrate( array $args, array $assoc ): void {
	$dry_run   = \WP_CLI\Utils\get_flag_value( $assoc, 'dry-run', false );
	$keep_old  = \WP_CLI\Utils\get_flag_value( $assoc, 'keep-old-published', false );
	$limit     = isset( $assoc['limit'] ) ? max( 0, (int) $assoc['limit'] ) : 0;

	$query = array(
		'post_type'              => 'post',
		'post_status'            => array( 'publish', 'draft', 'private', 'future' ),
		'category_name'          => 'now-updates',
		'posts_per_page'         => $limit > 0 ? $limit : -1,
		'orderby'                => 'date',
		'order'                  => 'ASC',
		'no_found_rows'          => true,
		'ignore_sticky_posts'    => true,
		'suppress_filters'       => false,
		'update_post_term_cache' => false,
	);
	$source_posts = get_posts( $query );
	if ( empty( $source_posts ) ) {
		WP_CLI::success( 'No source posts found in category now-updates.' );
		return;
	}

	$created = 0;
	$mapped  = 0;
	$skipped = 0;

	foreach ( $source_posts as $source ) {
		$source_id  = (int) $source->ID;
		$source_slug = (string) $source->post_name;

		$existing_id = (int) get_post_meta( $source_id, '_jardin_now_update_new_id', true );
		$existing    = $existing_id > 0 ? get_post( $existing_id ) : null;
		if ( $existing instanceof WP_Post && 'now_update' === $existing->post_type ) {
			++$mapped;
			WP_CLI::log( "Mapped: post {$source_id} -> now_update {$existing_id}" );
			continue;
		}

		$target = get_page_by_path( $source_slug, OBJECT, 'now_update' );
		if ( $target instanceof WP_Post ) {
			if ( ! $dry_run ) {
				update_post_meta( $source_id, '_jardin_now_update_new_id', (int) $target->ID );
				update_post_meta( (int) $target->ID, '_jardin_now_update_source_post_id', $source_id );
			}
			++$mapped;
			WP_CLI::log( "Reused slug match: post {$source_id} -> now_update {$target->ID}" );
			continue;
		}

		$insert = array(
			'post_type'      => 'now_update',
			'post_status'    => (string) $source->post_status,
			'post_title'     => (string) $source->post_title,
			'post_name'      => $source_slug,
			'post_content'   => (string) $source->post_content,
			'post_excerpt'   => (string) $source->post_excerpt,
			'post_date'      => (string) $source->post_date,
			'post_date_gmt'  => (string) $source->post_date_gmt,
			'post_author'    => (int) $source->post_author,
			'post_password'  => (string) $source->post_password,
			'comment_status' => (string) $source->comment_status,
			'ping_status'    => (string) $source->ping_status,
		);

		if ( $dry_run ) {
			++$created;
			WP_CLI::log( "Dry-run create: post {$source_id} -> now_update {$source_slug}" );
			continue;
		}

		$new_id = wp_insert_post( wp_slash( $insert ), true );
		if ( is_wp_error( $new_id ) ) {
			++$skipped;
			WP_CLI::warning( "Skipped post {$source_id}: " . $new_id->get_error_message() );
			continue;
		}

		$new_id = (int) $new_id;
		update_post_meta( $source_id, '_jardin_now_update_new_id', $new_id );
		update_post_meta( $new_id, '_jardin_now_update_source_post_id', $source_id );

		$thumb_id = (int) get_post_thumbnail_id( $source_id );
		if ( $thumb_id > 0 ) {
			set_post_thumbnail( $new_id, $thumb_id );
		}

		if ( ! $keep_old && in_array( (string) $source->post_status, array( 'publish', 'future' ), true ) ) {
			wp_update_post(
				array(
					'ID'          => $source_id,
					'post_status' => 'draft',
				)
			);
		}

		++$created;
		WP_CLI::log( "Created now_update {$new_id} from post {$source_id}" );
	}

	WP_CLI::success(
		sprintf(
			'Done. source=%d created=%d mapped=%d skipped=%d dry_run=%s',
			count( $source_posts ),
			$created,
			$mapped,
			$skipped,
			$dry_run ? 'yes' : 'no'
		)
	);
}
