<?php
/**
 * Register theme-owned dynamic blocks (no shortcode wrappers).
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/**
 * Register block types from /blocks/* (block.json + render.php + editor script).
 */
function jardin_register_theme_blocks(): void {
	$base = get_template_directory() . '/blocks/';
	foreach ( array( 'header-utilities', 'theme-toggle', 'copyright', 'post-engage', 'event-link-banner', 'project-inline-meta', 'project-header-meta', 'project-changelog' ) as $slug ) {
		$path = $base . $slug;
		if ( is_readable( $path . '/block.json' ) ) {
			register_block_type( $path );
		}
	}
}
add_action( 'init', 'jardin_register_theme_blocks' );
