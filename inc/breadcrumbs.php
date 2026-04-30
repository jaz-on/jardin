<?php
/**
 * Breadcrumb rendering.
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render Yoast breadcrumb markup for theme templates.
 *
 * @return string HTML breadcrumb.
 */
function jardin_render_breadcrumb(): string {
	if ( is_admin() || is_front_page() ) {
		return '';
	}

	if ( function_exists( 'yoast_breadcrumb' ) ) {
		$html = yoast_breadcrumb( '<p class="breadcrumb">', '</p>', false );
		return is_string( $html ) ? $html : '';
	}

	return '';
}

/**
 * Server-side render callback for the breadcrumb block.
 *
 * @param array<string,mixed> $attributes Block attributes.
 * @param string              $content Block content.
 * @return string
 */
function jardin_render_breadcrumb_block( array $attributes = array(), string $content = '' ): string {
	unset( $attributes, $content );
	return jardin_render_breadcrumb();
}

/**
 * Register the breadcrumb block for template usage.
 *
 * @return string
 */
function jardin_register_breadcrumb_block(): void {
	register_block_type(
		'jardin-theme/breadcrumb',
		array(
			'api_version'     => 2,
			'render_callback' => 'jardin_render_breadcrumb_block',
		)
	);
}
add_action( 'init', 'jardin_register_breadcrumb_block' );
