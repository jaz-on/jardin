<?php
/**
 * Optional rewrite rules (monthly `now` CPT URLs).
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register rewrite rules from jardin-docs integration/permalinks-rewrites.md.
 */
function jardin_register_rewrite_rules(): void {
	if ( ! defined( 'JARDIN_UPDATES_POST_TYPE' ) || ! post_type_exists( JARDIN_UPDATES_POST_TYPE ) ) {
		return;
	}

	$pt     = JARDIN_UPDATES_POST_TYPE;
	$target = 'index.php?post_type=' . $pt . '&name=$matches[1]';

	add_rewrite_rule( '^now/([0-9]{4}-[0-9]{2})/?$', $target, 'top' );
	add_rewrite_rule( '^maintenant/([0-9]{4}-[0-9]{2})/?$', $target, 'top' );
	add_rewrite_rule( '^en/now/([0-9]{4}-[0-9]{2})/?$', $target, 'top' );

	$legacy_cat = apply_filters( 'jardin_updates_legacy_category_slug', '' );
	if ( is_string( $legacy_cat ) && '' !== $legacy_cat ) {
		$legacy_cat = sanitize_title( $legacy_cat );
		if ( '' !== $legacy_cat ) {
			add_rewrite_rule(
				'^category/' . $legacy_cat . '/([0-9]{4}-[0-9]{2})/?$',
				'index.php?category_name=' . $legacy_cat . '&name=$matches[1]',
				'top'
			);
		}
	}
}
add_action( 'init', 'jardin_register_rewrite_rules' );

/**
 * Redirect legacy category singles to canonical `now` CPT URL when possible.
 */
function jardin_redirect_legacy_now_post_urls(): void {
	if ( ! defined( 'JARDIN_UPDATES_POST_TYPE' ) || ! post_type_exists( JARDIN_UPDATES_POST_TYPE ) ) {
		return;
	}

	if ( is_admin() || ! is_singular( 'post' ) ) {
		return;
	}

	$post = get_queried_object();
	$legacy_cat = apply_filters( 'jardin_updates_legacy_category_slug', '' );
	if ( ! is_string( $legacy_cat ) || '' === $legacy_cat ) {
		return;
	}
	$legacy_cat = sanitize_title( $legacy_cat );
	if ( '' === $legacy_cat || ! $post instanceof WP_Post || ! is_object_in_term( (int) $post->ID, 'category', $legacy_cat ) ) {
		return;
	}

	$migrated_id = (int) get_post_meta( (int) $post->ID, '_jardin_updates_update_new_id', true );
	$target      = $migrated_id > 0 ? get_post( $migrated_id ) : null;
	if ( ! $target instanceof WP_Post || JARDIN_UPDATES_POST_TYPE !== $target->post_type ) {
		$target = get_page_by_path( (string) $post->post_name, OBJECT, JARDIN_UPDATES_POST_TYPE );
	}
	if ( ! $target instanceof WP_Post || JARDIN_UPDATES_POST_TYPE !== $target->post_type ) {
		return;
	}

	$canonical = get_permalink( $target );
	if ( ! is_string( $canonical ) || '' === $canonical ) {
		return;
	}

	wp_safe_redirect( $canonical, 301 );
	exit;
}
add_action( 'template_redirect', 'jardin_redirect_legacy_now_post_urls', 1 );

/**
 * Flush rewrite rules when switching to this theme.
 */
function jardin_flush_rewrites_on_switch(): void {
	flush_rewrite_rules( false );
}
add_action( 'after_switch_theme', 'jardin_flush_rewrites_on_switch' );

/**
 * Flush rewrite rules after this theme is upgraded in place (Git Updater, ZIP, etc.).
 *
 * `after_switch_theme` does not run when files are replaced without a theme switch;
 * saving Permalinks in admin only triggers `flush_rewrite_rules()`. The upgrader hook
 * runs at the end of a successful theme update via the standard WordPress upgrader.
 *
 * @param WP_Upgrader $upgrader Upgrader instance (unused).
 * @param array       $hook_extra See Theme_Upgrader / `upgrader_process_complete`.
 */
function jardin_flush_rewrites_after_self_theme_upgrade( $upgrader, array $hook_extra ): void {
	if ( ! isset( $hook_extra['type'], $hook_extra['themes'] ) || 'theme' !== $hook_extra['type'] ) {
		return;
	}
	$active   = array_filter( array( (string) get_stylesheet(), (string) get_template() ) );
	$upgraded = array_map( 'strval', (array) $hook_extra['themes'] );
	if ( array_intersect( $active, $upgraded ) ) {
		flush_rewrite_rules( false );
	}
}
add_action( 'upgrader_process_complete', 'jardin_flush_rewrites_after_self_theme_upgrade', 10, 2 );

/**
 * Collect theme file paths whose mtime should trigger a rewrite flush after a Git / sync deploy
 * (no version bump, no Theme_Upgrader — common with Git Updater + branch webhooks).
 *
 * @return string[] Absolute paths.
 */
function jardin_rewrite_sentinel_paths(): array {
	$dirs = array( get_stylesheet_directory() );
	if ( is_child_theme() ) {
		$dirs[] = get_template_directory();
	}
	$paths = array();
	foreach ( array_unique( $dirs ) as $dir ) {
		foreach ( array( $dir . '/functions.php', $dir . '/style.css' ) as $path ) {
			if ( is_readable( $path ) ) {
				$paths[] = $path;
			}
		}
		$inc = glob( $dir . '/inc/*.php' );
		if ( is_array( $inc ) ) {
			foreach ( $inc as $path ) {
				if ( is_readable( $path ) ) {
					$paths[] = $path;
				}
			}
		}
	}
	/**
	 * Extra files to watch for mtime (e.g. `{$dir}/inc/rewrites/foo.php` if split differently).
	 *
	 * @param string[] $paths Absolute paths.
	 */
	return array_unique( array_merge( $paths, apply_filters( 'jardin_rewrite_sentinel_extra_paths', array() ) ) );
}

/**
 * Latest mtime among sentinel theme files (rewrite registration lives under inc/ + bootstrap).
 */
function jardin_get_theme_rewrite_sentinel_mtime(): int {
	$max = 0;
	foreach ( jardin_rewrite_sentinel_paths() as $path ) {
		$max = max( $max, (int) filemtime( $path ) );
	}
	return $max;
}

/**
 * After a file-based theme deploy, flush rewrite rules once when sentinel mtimes advance.
 *
 * Runs on `wp_loaded` so all `init` rewrite registrations are in place before flush.
 */
function jardin_maybe_flush_rewrites_after_theme_pull(): void {
	if ( wp_installing() ) {
		return;
	}
	if ( ! apply_filters( 'jardin_auto_flush_rewrites_on_theme_pull', true ) ) {
		return;
	}
	$mtime = jardin_get_theme_rewrite_sentinel_mtime();
	if ( $mtime <= 0 ) {
		return;
	}
	$key    = 'jardin_theme_rewrite_sentinel_mtime';
	$stored = (int) get_option( $key, 0 );
	if ( $mtime <= $stored ) {
		return;
	}
	flush_rewrite_rules( false );
	update_option( $key, $mtime, false );
}
add_action( 'wp_loaded', 'jardin_maybe_flush_rewrites_after_theme_pull', 1 );
