<?php
/**
 * Front-end and editor assets.
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue global theme styles and scripts.
 */
function jardin_enqueue_assets(): void {
	$ver     = wp_get_theme()->get( 'Version' );
	$tpl_dir = get_template_directory();

	wp_enqueue_style(
		'jardin-theme-base',
		get_template_directory_uri() . '/assets/css/theme-base.css',
		array(),
		filemtime( $tpl_dir . '/assets/css/theme-base.css' ) ?: $ver
	);

	wp_enqueue_style(
		'jardin-theme-all',
		get_template_directory_uri() . '/assets/themes/all.css',
		array( 'jardin-theme-base' ),
		filemtime( $tpl_dir . '/assets/themes/all.css' ) ?: $ver
	);

	wp_enqueue_script(
		'jardin-theme-toggle',
		get_template_directory_uri() . '/assets/js/theme-toggle.js',
		array(),
		filemtime( $tpl_dir . '/assets/js/theme-toggle.js' ) ?: $ver,
		false
	);

	wp_enqueue_script(
		'jardin-theme-nav-burger',
		get_template_directory_uri() . '/assets/js/nav-burger.js',
		array(),
		filemtime( $tpl_dir . '/assets/js/nav-burger.js' ) ?: $ver,
		true
	);

	wp_enqueue_script(
		'jardin-theme-filter-tabs',
		get_template_directory_uri() . '/assets/js/filter-tabs.js',
		array(),
		filemtime( $tpl_dir . '/assets/js/filter-tabs.js' ) ?: $ver,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'jardin_enqueue_assets' );
