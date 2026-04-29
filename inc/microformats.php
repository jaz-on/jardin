<?php
/**
 * Heuristic microformat classes on public singles (h-entry, h-event, h-review) — jardin-docs integration/.
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/**
 * Add h-* / u-* class hooks on the `post` wrapper for indieweb parsers.
 *
 * @param string[]   $classes Class list.
 * @param string|array $class   (unused) class arg.
 * @param int         $post_id  Post ID.
 * @return string[]
 */
function jardin_mf_post_class( array $classes, $class, $post_id ): array {
	$post = get_post( (int) $post_id );
	if ( ! $post || is_admin() ) {
		return $classes;
	}

	$pt = (string) $post->post_type;
	switch ( $pt ) {
		case 'event':
			$classes[] = 'h-entry';
			$classes[] = 'h-event';
			break;
		case 'beer_checkin':
			$classes[] = 'h-review';
			break;
		case 'listen':
			$classes[] = 'h-entry';
			$classes[] = 'u-listen-of';
			break;
		case 'blogroll':
			$classes[] = 'h-card';
			break;
		case 'favorite':
			$classes[] = 'h-entry';
			break;
		case 'post':
		case 'iwcpt_note':
		case 'iwcpt_like':
			$classes[] = 'h-entry';
			break;
	}
	$classes = array_values( array_unique( $classes ) );
	return $classes;
}
add_filter( 'post_class', 'jardin_mf_post_class', 12, 3 );
