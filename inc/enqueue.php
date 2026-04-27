<?php
/**
 * Front-end and editor assets.
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue global theme styles and scripts.
 */
function jardin_enqueue_assets(): void {
	$ver = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'jardin-theme-base',
		get_template_directory_uri() . '/assets/css/theme-base.css',
		array(),
		$ver
	);

	wp_enqueue_style(
		'jardin-theme-all',
		get_template_directory_uri() . '/assets/themes/all.css',
		array( 'jardin-theme-base' ),
		$ver
	);

	wp_enqueue_script(
		'jardin-theme-toggle',
		get_template_directory_uri() . '/assets/js/theme-toggle.js',
		array(),
		$ver,
		false
	);

	wp_enqueue_script(
		'jardin-nav-burger',
		get_template_directory_uri() . '/assets/js/nav-burger.js',
		array(),
		$ver,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'jardin_enqueue_assets' );
