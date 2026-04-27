<?php
/**
 * Editor script: Query Loop block variations.
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue block variation script in the block editor.
 */
function jardin_enqueue_block_variations_editor(): void {
	wp_enqueue_script(
		'jardin-block-variations',
		get_template_directory_uri() . '/assets/js/block-variations.js',
		array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-dom-ready' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'jardin_enqueue_block_variations_editor' );
