<?php
/**
 * POSSE, webmention, and OpenGraph glue for custom CPTs (plugins may be inactive — filters are harmless no-ops).
 *
 * @package Jardin
 */

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
	if ( ! $post || ! in_array( (string) $post->post_type, array( 'post' ), true ) ) {
		return $text;
	}
	if ( ! is_object_in_term( (int) $post->ID, 'category', 'now-updates' ) ) {
		return $text;
	}
	$prefix = '[' . get_bloginfo( 'name' ) . ' — ' . _x( 'now', 'short label for the now-updates format', 'jardin' ) . "] \n\n";
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
	$our = array( 'event', 'favorite', 'blogroll', 'beer_checkin', 'listen', 'iwcpt_note', 'iwcpt_like' );
	$out = array_merge( (array) $types, $our );
	$out = array_values( array_unique( array_map( 'sanitize_key', $out ) ) );
	return $out;
}
add_filter( 'webmention_post_types', 'jardin_webmention_post_types' );

/**
 * Sensible Open Graph types per CPT.
 */
function jardin_opengraph_type( $type, $post ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return $type;
	}
	return match ( (string) $post->post_type ) {
		'event'        => 'event',
		'beer_checkin' => 'product',
		'listen'       => 'music.song',
		default        => $type,
	};
}
add_filter( 'opengraph_type', 'jardin_opengraph_type', 12, 2 );
