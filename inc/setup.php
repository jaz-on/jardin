<?php
/**
 * Theme setup: supports, text domain, block theme basics.
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load theme textdomain and register theme supports.
 */
function jardin_setup(): void {
	load_theme_textdomain( 'jardin', get_template_directory() . '/languages' );

	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/theme-base.css' );

	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'jardin_setup' );
