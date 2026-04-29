<?php
/**
 * Translatable placeholder copy for PHP block patterns.
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/**
 * Message body for placeholder-* patterns (no HTML).
 *
 * @param string $type now|beers|blogroll|generic
 * @return string
 */
function jardin_get_placeholder_message( string $type ): string {
	switch ( sanitize_key( $type ) ) {
		case 'now':
			return __( 'Live “now” summary blocks (for example Last.fm) will load here once the listening plugin is connected. Page body below remains free for editorial content.', 'jardin-theme' );
		case 'beers':
			return __( 'Recent check-ins and beer grids from jardin-beer will appear here in a later phase. Use the page content for any introductory copy.', 'jardin-theme' );
		case 'blogroll':
			return __( 'The blogroll grid powered by jardin-feed will appear here in a later phase. Use the page content for notes in the meantime.', 'jardin-theme' );
		default:
			return __( 'Content for this section will appear here once the related features are connected.', 'jardin-theme' );
	}
}
