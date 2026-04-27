<?php
/**
 * Optional rewrite rules (now-updates monthly URLs).
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register rewrite rules from jardin-docs integration/permalinks-rewrites.md.
 */
function jardin_register_rewrite_rules(): void {
	add_rewrite_rule(
		'^now-updates/([0-9]{4}-[0-9]{2})/?$',
		'index.php?category_name=now-updates&name=$matches[1]',
		'top'
	);
}
add_action( 'init', 'jardin_register_rewrite_rules' );

/**
 * Pretty now-update URLs: `/now-updates/{year-month}/` (Polylang may translate the segment; filter `jardin_now_updates_path` can override the base).
 *
 * @param string  $url  Permalink.
 * @param \WP_Post $post Post.
 * @return string
 */
function jardin_filter_now_updates_post_link( $url, $post ) {
	if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		return $url;
	}
	if ( 'post' !== $post->post_type || 'publish' !== $post->post_status ) {
		return $url;
	}
	if ( ! is_object_in_term( (int) $post->ID, 'category', 'now-updates' ) ) {
		return $url;
	}
	$slug  = (string) $post->post_name;
	$base  = (string) apply_filters( 'jardin_now_updates_path', 'now-updates' );
	$built = home_url( user_trailingslashit( $base . '/' . $slug ) );
	return $built;
}
add_filter( 'post_link', 'jardin_filter_now_updates_post_link', 20, 2 );

/**
 * Flush rewrite rules when switching to this theme.
 */
function jardin_flush_rewrites_on_switch(): void {
	flush_rewrite_rules( false );
}
add_action( 'after_switch_theme', 'jardin_flush_rewrites_on_switch' );
