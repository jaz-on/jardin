<?php
/**
 * Core sitemap tweaks (e.g. exclude listen CPT from default sitemap when desired).
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Allow disabling listen CPT in sitemaps via filter (default: keep for Phase 2).
 *
 * @param array   $post_types Post types in sitemap.
 * @param string  $sitemap    Sitemap name.
 * @return array
 */
function jardin_sitemaps_post_types( array $post_types ): array {
	/**
	 * Filter: jardin_sitemap_exclude_listen — return true to omit `listen` from sitemaps.
	 *
	 * @param bool $exclude Whether to exclude listen CPT.
	 */
	if ( apply_filters( 'jardin_sitemap_exclude_listen', false ) && isset( $post_types['listen'] ) ) {
		unset( $post_types['listen'] );
	}

	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'jardin_sitemaps_post_types', 10, 1 );
