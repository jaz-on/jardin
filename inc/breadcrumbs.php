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
 * Yoast often still shows “Bières” for the Untappd stats hub; align the last crumb with the product label.
 *
 * @param array<int, array<string, mixed>>|mixed $links Breadcrumb trail.
 * @return array<int, array<string, mixed>>|mixed
 */
function jardin_filter_yoast_breadcrumb_toasts_hub( $links ) {
	if ( ! is_array( $links ) || ! is_page() ) {
		return $links;
	}
	$slug = (string) get_post_field( 'post_name', (int) get_queried_object_id(), 'raw' );
	if ( ! in_array( $slug, array( 'toast', 'bieres', 'toasts', 'beers' ), true ) ) {
		return $links;
	}
	$label = __( 'Toasts', 'jardin-theme' );
	$last  = count( $links ) - 1;
	if ( $last < 0 ) {
		return $links;
	}
	if ( isset( $links[ $last ]['text'] ) ) {
		$links[ $last ]['text'] = $label;
	}
	return $links;
}
add_filter( 'wpseo_breadcrumb_links', 'jardin_filter_yoast_breadcrumb_toasts_hub', 20 );

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
