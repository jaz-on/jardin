<?php
/**
 * Register block styles (see jardin-docs theme/blocks_inventory.md section D).
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register all theme block styles.
 */
function jardin_register_block_styles(): void {
	$definitions = array(
		array( 'core/paragraph', 'lead', __( 'Lead', 'jardin' ) ),
		array( 'core/heading', 'mono-label', __( 'Mono label', 'jardin' ) ),
		array( 'core/list', 'bare', __( 'Bare list', 'jardin' ) ),
		array( 'core/list', 'inline', __( 'Inline list', 'jardin' ) ),
		array( 'core/quote', 'pull-quote', __( 'Pull quote', 'jardin' ) ),
		array( 'core/details', 'techie', __( 'Technical note', 'jardin' ) ),
		array( 'core/image', 'portrait', __( 'Portrait 3:4', 'jardin' ) ),
		array( 'core/image', 'square', __( 'Square', 'jardin' ) ),
		array( 'core/cover', 'feature', __( 'Feature hero', 'jardin' ) ),
		array( 'core/group', 'callout-soft', __( 'Callout soft', 'jardin' ) ),
		array( 'core/group', 'callout-info', __( 'Callout info', 'jardin' ) ),
		array( 'core/group', 'card', __( 'Card', 'jardin' ) ),
		array( 'core/group', 'card-highlight', __( 'Card highlight', 'jardin' ) ),
		array( 'core/group', 'snap-card', __( 'Carousel snap', 'jardin' ) ),
		array( 'core/group', 'single-footer-zone', __( 'Single footer zone', 'jardin' ) ),
		array( 'core/columns', 'asymmetric', __( 'Asymmetric 30/70', 'jardin' ) ),
		array( 'core/columns', '3-cols', __( 'Three columns', 'jardin' ) ),
		array( 'core/separator', 'dotted', __( 'Dotted', 'jardin' ) ),
		array( 'core/separator', 'dashed-faint', __( 'Faint dashes', 'jardin' ) ),
		array( 'core/button', 'ghost', __( 'Ghost', 'jardin' ) ),
		array( 'core/button', 'coffee-toggle', __( 'Coffee toggle', 'jardin' ) ),
		array( 'core/post-terms', 'mono-label', __( 'Mono uppercase', 'jardin' ) ),
		array( 'core/navigation', 'tabs', __( 'Tabs', 'jardin' ) ),
	);

	foreach ( $definitions as $row ) {
		register_block_style(
			$row[0],
			array(
				'name'  => $row[1],
				'label' => $row[2],
			)
		);
	}

}
add_action( 'init', 'jardin_register_block_styles', 20 );

/**
 * Enqueue shared CSS when block styles are used.
 */
function jardin_enqueue_block_styles_assets(): void {
	$targets = array(
		'core/paragraph',
		'core/heading',
		'core/list',
		'core/quote',
		'core/details',
		'core/image',
		'core/cover',
		'core/group',
		'core/columns',
		'core/separator',
		'core/button',
		'core/post-terms',
		'core/navigation',
	);

	foreach ( $targets as $block_name ) {
		wp_enqueue_block_style(
			$block_name,
			array(
				'handle' => 'jardin-block-styles',
				'src'    => get_theme_file_uri( 'assets/css/block-styles.css' ),
				'path'   => get_theme_file_path( 'assets/css/block-styles.css' ),
			)
		);
	}
}
add_action( 'init', 'jardin_enqueue_block_styles_assets', 30 );
