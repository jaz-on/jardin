<?php
/**
 * CPT singles under the same path prefix as a hub page (/projects/, /events/, …).
 *
 * WordPress resolves hierarchical pages first: a URL like /{hub}/{slug} is treated as a
 * child page before the CPT, which yields a 404 even when published CPT content exists.
 *
 * We fix this at `request`: if no page matches the path and a post of the expected CPT
 * (rewrite slug = first segment) exists, switch to a single-CPT query.
 *
 * Prefix → CPT pairs are built from public CPTs with rewrite slugs (plugins included).
 * Use the `jardin_hub_page_cpt_prefix_map` filter to remove or force an entry.
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Map URL segment → post_type (WordPress CPT rewrite slug).
 *
 * @return array<string, string>
 */
function jardin_get_hub_page_cpt_prefix_map(): array {
	static $map = null;
	if ( null !== $map ) {
		return $map;
	}

	if ( ! did_action( 'init' ) ) {
		return apply_filters( 'jardin_hub_page_cpt_prefix_map', array() );
	}

	$built = array();
	foreach ( get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' ) as $post_type => $obj ) {
		if ( ! $obj instanceof WP_Post_Type || ! $obj->publicly_queryable ) {
			continue;
		}
		if ( empty( $obj->rewrite ) || ! is_array( $obj->rewrite ) ) {
			continue;
		}
		$slug = isset( $obj->rewrite['slug'] ) ? (string) $obj->rewrite['slug'] : '';
		if ( '' === $slug || str_contains( $slug, '/' ) ) {
			continue;
		}
		$built[ $slug ] = $post_type;
	}

	// Longest prefix first (edge case: nested prefixes).
	uksort(
		$built,
		static function ( string $a, string $b ): int {
			return strlen( $b ) <=> strlen( $a );
		}
	);

	$map = apply_filters( 'jardin_hub_page_cpt_prefix_map', $built );

	return $map;
}

/**
 * @param array<string, mixed> $query_vars Query vars.
 * @return array<string, mixed>
 */
function jardin_request_cpt_single_under_hub_prefix( array $query_vars ): array {
	if ( is_admin() || empty( $query_vars['pagename'] ) || isset( $query_vars['preview'] ) ) {
		return $query_vars;
	}

	$map = jardin_get_hub_page_cpt_prefix_map();
	if ( empty( $map ) ) {
		return $query_vars;
	}

	$pagename = rawurldecode( (string) $query_vars['pagename'] );

	foreach ( $map as $prefix => $post_type ) {
		if ( ! preg_match( '#^' . preg_quote( $prefix, '#' ) . '/([^/]+)$#', $pagename, $matches ) ) {
			continue;
		}

		if ( ! post_type_exists( $post_type ) ) {
			continue;
		}

		if ( get_page_by_path( $pagename, OBJECT, 'page' ) instanceof WP_Post ) {
			continue;
		}

		$slug = $matches[1];

		$exists = new WP_Query(
			array(
				'post_type'              => $post_type,
				'name'                   => $slug,
				'post_status'            => 'publish',
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'ignore_sticky_posts'    => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
			)
		);

		if ( empty( $exists->posts ) ) {
			continue;
		}

		unset( $query_vars['pagename'], $query_vars['error'], $query_vars['attachment'] );

		$query_vars['post_type'] = $post_type;
		$query_vars['name']      = $slug;

		return $query_vars;
	}

	return $query_vars;
}
add_filter( 'request', 'jardin_request_cpt_single_under_hub_prefix', 11 );
