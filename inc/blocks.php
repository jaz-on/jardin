<?php
/**
 * Register theme-owned dynamic blocks (no shortcode wrappers).
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/**
 * Register block types from /blocks/* (block.json + render.php + editor script).
 *
 * Editor scripts are registered here with explicit script dependencies. Relying
 * only on block.json "editorScript": "file:./index.js" can enqueue the file
 * before the block editor globals exist (e.g. site editor iframe), causing
 * "wp is not defined" and unsupported blocks in the canvas.
 */
function jardin_register_theme_blocks(): void {
	$base     = get_template_directory() . '/blocks/';
	$uri_base = get_template_directory_uri() . '/blocks/';
	$slugs    = array( 'header-utilities', 'theme-toggle', 'copyright', 'post-engage', 'event-link-banner' );

	$editor_deps = array(
		'wp-blocks',
		'wp-element',
		'wp-block-editor',
		'wp-components',
		'wp-i18n',
	);

	foreach ( $slugs as $slug ) {
		$path = $base . $slug;
		if ( ! is_readable( $path . '/block.json' ) ) {
			continue;
		}

		$args = array();

		if ( is_readable( $path . '/index.js' ) ) {
			$handle = 'jardin-theme-editor-' . $slug;
			$ver    = (string) ( filemtime( $path . '/index.js' ) ?: wp_get_theme()->get( 'Version' ) );
			wp_register_script(
				$handle,
				$uri_base . $slug . '/index.js',
				$editor_deps,
				$ver,
				true
			);
			$args['editor_script'] = $handle;
		}

		register_block_type( $path, $args );
	}
}
add_action( 'init', 'jardin_register_theme_blocks' );
