<?php
/**
 * Footer columns « This site » / « Reach me » : paths filterable without hard-coded home_url only.
 *
 * Override URLs via {@see 'jardin_secondary_footer_url'} or paths via {@see 'jardin_secondary_footer_path'}.
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default path segment (leading slash, trailing slash) for a footer secondary link key.
 *
 * @param string $key Stable key: index, colophon, flux, styleguide, ia, mentions_legales, contact, social.
 * @return string
 */
function jardin_get_secondary_footer_default_path( string $key ): string {
	$defaults = array(
		'index'            => '/index/',
		'colophon'         => '/colophon/',
		'flux'             => '/flux/',
		'styleguide'       => '/styleguide/',
		'ia'               => '/ia/',
		'mentions_legales' => '/mentions-legales/',
		'contact'          => '/contact/',
		'social'           => '/social/',
	);
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '/';
}

/**
 * Resolved absolute URL (not escaped) for footer secondary links.
 *
 * @param string $key Stable key.
 * @return string
 */
function jardin_resolve_secondary_footer_url( string $key ): string {
	$path = jardin_get_secondary_footer_default_path( $key );
	/**
	 * Filter path before resolving home URL (leading slash, trailing slash preferred).
	 *
	 * @param string $path Default path for $key.
	 * @param string $key  Link key.
	 */
	$path = (string) apply_filters( 'jardin_secondary_footer_path', $path, $key );
	$path = '/' . trim( $path, '/' ) . '/';
	$url  = home_url( $path );
	/**
	 * Filter resolved footer secondary URL.
	 *
	 * @param string $url Full URL.
	 * @param string $key Link key.
	 */
	return (string) apply_filters( 'jardin_secondary_footer_url', $url, $key );
}

/**
 * Escaped URL for footer secondary links (This site / Reach me).
 *
 * @param string $key Stable key (see defaults in jardin_get_secondary_footer_default_path()).
 * @return string
 */
function jardin_get_secondary_footer_url( string $key ): string {
	return esc_url( jardin_resolve_secondary_footer_url( $key ) );
}

/**
 * Visible path label for footer secondary links (matches prior mockup strings where needed).
 *
 * @param string $key Stable key.
 * @return string
 */
function jardin_get_secondary_footer_label( string $key ): string {
	$labels = array(
		'index'            => '/index',
		'colophon'         => '/colophon',
		'flux'             => '/flux',
		'styleguide'       => '/styleguide',
		'ia'               => '/ia',
		'mentions_legales' => '/mentions-légales',
		'contact'          => '/contact',
		'social'           => '/social',
	);
	$default = isset( $labels[ $key ] ) ? $labels[ $key ] : '';
	if ( '' === $default && function_exists( 'jardin_get_path_label_for_url' ) ) {
		$default = jardin_get_path_label_for_url( jardin_resolve_secondary_footer_url( $key ) );
	}
	if ( '' === $default ) {
		$default = '/' . $key;
	}
	/**
	 * Filter footer secondary link label (path-style display).
	 *
	 * @param string $label Default label.
	 * @param string $key   Link key.
	 */
	return (string) apply_filters( 'jardin_secondary_footer_label', $default, $key );
}
