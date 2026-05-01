<?php
/**
 * POSSE, webmention, and OpenGraph glue for custom CPTs (plugins may be inactive — filters are harmless no-ops).
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/**
 * @param \WP_Post|null $post Post.
 * @return bool Whether this post has editorial body content.
 */
function jardin_post_has_editorial_content( $post ): bool {
	if ( ! $post instanceof WP_Post ) {
		return false;
	}
	return '' !== trim( (string) $post->post_content );
}

/**
 * Do not cross-post raw scrobbles; still allow *jams* (listens with content).
 */
function jardin_mastodon_skip_raw_listens( $enabled, $post ) {
	$post = get_post( $post );
	if ( ! $post || 'listen' !== $post->post_type ) {
		return $enabled;
	}
	if ( jardin_post_has_editorial_content( $post ) ) {
		return $enabled;
	}
	return false;
}
add_filter( 'share_on_mastodon_enabled', 'jardin_mastodon_skip_raw_listens', 12, 2 );
add_filter( 'share_on_bluesky_enabled', 'jardin_mastodon_skip_raw_listens', 12, 2 );

/**
 * Optional status tweaks: keep the default; hook exists for per-site use.
 */
function jardin_mastodon_status( $text, $post ) {
	$post = get_post( $post );
	if ( ! $post || ! in_array( (string) $post->post_type, array( 'post', JARDIN_NOW_POST_TYPE ), true ) ) {
		return $text;
	}
	if ( JARDIN_NOW_POST_TYPE !== (string) $post->post_type ) {
		return $text;
	}
	$prefix = '[' . get_bloginfo( 'name' ) . ' — ' . _x( 'now', 'short label for the now-updates format', 'jardin-theme' ) . "] \n\n";
	/**
	 * Filter: jardin_mastodon_now_update_prefix
	 */
	return apply_filters( 'jardin_mastodon_now_update_prefix', $prefix, $post ) . $text;
}
add_filter( 'share_on_mastodon_status', 'jardin_mastodon_status', 8, 2 );
add_filter( 'share_on_bluesky_status', 'jardin_mastodon_status', 8, 2 );

/**
 * Enable webmention endpoints on all first-party public CPTs.
 */
function jardin_webmention_post_types( array $types ): array {
	$our = array( 'post', 'project', 'event', 'favorite', 'blogroll', 'beer_checkin', 'listen', 'iwcpt_note', 'iwcpt_like', JARDIN_NOW_POST_TYPE );
	$out = array_merge( (array) $types, $our );
	$out = array_values( array_unique( array_map( 'sanitize_key', $out ) ) );
	return $out;
}
add_filter( 'webmention_post_types', 'jardin_webmention_post_types' );

/**
 * Sensible Open Graph types per CPT.
 * The Open Graph plugin may call `opengraph_type` with only `$type` (no post); resolve from the main query.
 *
 * @param string        $type Default og:type.
 * @param int|\WP_Post|null $post Optional post (when the filter passes it).
 * @return string
 */
function jardin_opengraph_type( $type, $post = null ) {
	if ( $post ) {
		$res = get_post( $post );
	} else {
		$q   = get_queried_object();
		$res = ( $q instanceof WP_Post ) ? $q : null;
	}
	if ( ! $res ) {
		return is_string( $type ) ? $type : (string) $type;
	}
	$pt = (string) $res->post_type;
	return match ( $pt ) {
		'event'        => 'event',
		'beer_checkin' => 'product',
		'listen'       => 'music.song',
		default        => is_string( $type ) ? $type : (string) $type,
	};
}
add_filter( 'opengraph_type', 'jardin_opengraph_type', 12, 2 );
