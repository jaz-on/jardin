<?php
/**
 * Optional rewrite rules (now-update monthly URLs).
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register rewrite rules from jardin-docs integration/permalinks-rewrites.md.
 */
function jardin_register_rewrite_rules(): void {
	$target = 'index.php?post_type=now_update&name=$matches[1]';

	add_rewrite_rule( '^now-updates/([0-9]{4}-[0-9]{2})/?$', $target, 'top' );
	add_rewrite_rule( '^maintenant/([0-9]{4}-[0-9]{2})/?$', $target, 'top' );
	add_rewrite_rule( '^en/now-updates/([0-9]{4}-[0-9]{2})/?$', $target, 'top' );

	// Legacy category URLs retained for 301 canonical redirects.
	add_rewrite_rule( '^category/now-updates/([0-9]{4}-[0-9]{2})/?$', 'index.php?category_name=now-updates&name=$matches[1]', 'top' );
	add_rewrite_rule( '^categorie/maintenant/([0-9]{4}-[0-9]{2})/?$', 'index.php?category_name=now-updates&name=$matches[1]', 'top' );
}
add_action( 'init', 'jardin_register_rewrite_rules' );

/**
 * Redirect legacy category singles to canonical now_update URL when possible.
 */
function jardin_redirect_legacy_now_update_urls(): void {
	if ( is_admin() || ! is_singular( 'post' ) ) {
		return;
	}

	$post = get_queried_object();
	if ( ! $post instanceof WP_Post || ! is_object_in_term( (int) $post->ID, 'category', 'now-updates' ) ) {
		return;
	}

	$migrated_id = (int) get_post_meta( (int) $post->ID, '_jardin_now_update_new_id', true );
	$target      = $migrated_id > 0 ? get_post( $migrated_id ) : null;
	if ( ! $target instanceof WP_Post || 'now_update' !== $target->post_type ) {
		$target = get_page_by_path( (string) $post->post_name, OBJECT, 'now_update' );
	}
	if ( ! $target instanceof WP_Post || 'now_update' !== $target->post_type ) {
		return;
	}

	$canonical = get_permalink( $target );
	if ( ! is_string( $canonical ) || '' === $canonical ) {
		return;
	}

	wp_safe_redirect( $canonical, 301 );
	exit;
}
add_action( 'template_redirect', 'jardin_redirect_legacy_now_update_urls', 1 );

/**
 * Flush rewrite rules when switching to this theme.
 */
function jardin_flush_rewrites_on_switch(): void {
	flush_rewrite_rules( false );
}
add_action( 'after_switch_theme', 'jardin_flush_rewrites_on_switch' );
