<?php
/**
 * Polylang + PHP block patterns: gettext and hub URLs must reflect the *current* language.
 *
 * Theme patterns are registered on {@see 'init'}; Polylang often resolves the request language
 * slightly later, so pattern HTML cached in {@see WP_Block_Patterns_Registry} can stay on the
 * default locale. We reload the textdomain once Polylang is ready and re-include PHP patterns
 * at render time for `core/pattern` blocks.
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Resolve a theme pattern PHP file from a registry slug (e.g. jardin-theme/home-feed).
 */
function jardin_theme_pattern_php_path( string $slug ): ?string {
	if ( ! preg_match( '/^jardin-theme\/(?P<base>[a-z0-9-]+)$/', $slug, $m ) ) {
		return null;
	}
	$file = get_template_directory() . '/patterns/' . $m['base'] . '.php';
	return is_readable( $file ) ? $file : null;
}

/**
 * Reload theme translations after Polylang sets the current language.
 */
function jardin_reload_theme_textdomain_after_pll(): void {
	unload_textdomain( 'jardin-theme' );
	load_theme_textdomain( 'jardin-theme', get_template_directory() . '/languages' );
}
add_action( 'pll_init', 'jardin_reload_theme_textdomain_after_pll', 0 );

/**
 * Render PHP theme patterns at request time so gettext + Polylang-aware helpers use the active locale.
 *
 * @param string|null $pre_render Pre-rendered output.
 * @param array       $parsed_block Parsed block.
 * @return string|null
 */
function jardin_pre_render_core_pattern_block( $pre_render, array $parsed_block ) {
	if ( null !== $pre_render ) {
		return $pre_render;
	}
	if ( ( $parsed_block['blockName'] ?? '' ) !== 'core/pattern' ) {
		return null;
	}
	$attrs = $parsed_block['attrs'] ?? array();
	if ( ! is_array( $attrs ) ) {
		return null;
	}
	// Synced / saved patterns in the DB — do not override.
	if ( ! empty( $attrs['ref'] ) ) {
		return null;
	}
	$slug = isset( $attrs['slug'] ) ? (string) $attrs['slug'] : '';
	$file = jardin_theme_pattern_php_path( $slug );
	if ( ! $file ) {
		return null;
	}

	ob_start();
	include $file; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
	$markup = (string) ob_get_clean();
	if ( '' === trim( $markup ) ) {
		return null;
	}

	return do_blocks( $markup );
}
add_filter( 'pre_render_block', 'jardin_pre_render_core_pattern_block', 10, 2 );
