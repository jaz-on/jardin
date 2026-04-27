<?php
/**
 * Front-end and editor assets (Phase 2.1: base CSS only).
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue global theme stylesheet.
 */
function jardin_enqueue_assets(): void {
	$ver = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'jardin-theme-base',
		get_template_directory_uri() . '/assets/css/theme-base.css',
		array(),
		$ver
	);
}
add_action( 'wp_enqueue_scripts', 'jardin_enqueue_assets' );
