<?php
/**
 * Register block styles (see jardin-docs theme/blocks_inventory.md section D).
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/**
 * Register all theme block styles.
 */
function jardin_register_block_styles(): void {
	$definitions = array(
		array( 'core/paragraph', 'lead', __( 'Lead', 'jardin-theme' ) ),
		array( 'core/paragraph', 'text-meta-sm', __( 'Meta text small', 'jardin-theme' ) ),
		array( 'core/paragraph', 'text-meta-base', __( 'Meta text base', 'jardin-theme' ) ),
		array( 'core/heading', 'mono-label', __( 'Mono label', 'jardin-theme' ) ),
		array( 'core/heading', 'stat-label', __( 'Stat label', 'jardin-theme' ) ),
		array( 'core/list', 'bare', __( 'Bare list', 'jardin-theme' ) ),
		array( 'core/list', 'inline', __( 'Inline list', 'jardin-theme' ) ),
		array( 'core/list', 'none', __( 'No bullets', 'jardin-theme' ) ),
		array( 'core/list-item', 'bullet-row', __( 'Bullet row', 'jardin-theme' ) ),
		array( 'core/list-item', 'bullet-row-solid', __( 'Bullet row solid', 'jardin-theme' ) ),
		array( 'core/quote', 'pull-quote', __( 'Pull quote', 'jardin-theme' ) ),
		array( 'core/quote', 'quote-card', __( 'Quote card', 'jardin-theme' ) ),
		array( 'core/details', 'techie', __( 'Technical note', 'jardin-theme' ) ),
		array( 'core/image', 'portrait', __( 'Portrait 3:4', 'jardin-theme' ) ),
		array( 'core/image', 'square', __( 'Square', 'jardin-theme' ) ),
		array( 'core/image', 'icon-inline', __( 'Inline icon', 'jardin-theme' ) ),
		array( 'core/cover', 'feature', __( 'Feature hero', 'jardin-theme' ) ),
		array( 'core/group', 'callout-soft', __( 'Callout soft', 'jardin-theme' ) ),
		array( 'core/group', 'callout-info', __( 'Callout info', 'jardin-theme' ) ),
		array( 'core/group', 'callout-pad-sm', __( 'Callout compact', 'jardin-theme' ) ),
		array( 'core/group', 'card', __( 'Card', 'jardin-theme' ) ),
		array( 'core/group', 'card-highlight', __( 'Card highlight', 'jardin-theme' ) ),
		array( 'core/group', 'snap-card', __( 'Carousel snap', 'jardin-theme' ) ),
		array( 'core/group', 'avatar-circle', __( 'Avatar circle', 'jardin-theme' ) ),
		array( 'core/group', 'caption', __( 'Caption', 'jardin-theme' ) ),
		array( 'core/group', 'mb-stat', __( 'Microblog stat', 'jardin-theme' ) ),
		array( 'core/group', 'single-footer-zone', __( 'Single footer zone', 'jardin-theme' ) ),
		array( 'core/columns', 'asymmetric', __( 'Asymmetric 30/70', 'jardin-theme' ) ),
		array( 'core/columns', '3-cols', __( 'Three columns', 'jardin-theme' ) ),
		array( 'core/separator', 'dotted', __( 'Dotted', 'jardin-theme' ) ),
		array( 'core/separator', 'dashed-faint', __( 'Faint dashes', 'jardin-theme' ) ),
		array( 'core/button', 'ghost', __( 'Ghost', 'jardin-theme' ) ),
		array( 'core/button', 'coffee-toggle', __( 'Coffee toggle', 'jardin-theme' ) ),
		array( 'core/post-terms', 'mono-label', __( 'Mono uppercase', 'jardin-theme' ) ),
		array( 'core/navigation', 'tabs', __( 'Tabs', 'jardin-theme' ) ),
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
		'core/list-item',
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
				'handle' => 'jardin-theme-block-styles',
				'src'    => get_theme_file_uri( 'assets/css/block-styles.css' ),
				'path'   => get_theme_file_path( 'assets/css/block-styles.css' ),
			)
		);
	}
}
add_action( 'init', 'jardin_enqueue_block_styles_assets', 30 );
