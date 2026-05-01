<?php
/**
 * Server output for jardin-theme/header-utilities.
 *
 * @package Jardin_Theme
 * @var array<string, mixed> $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$variant = isset( $attributes['variant'] ) ? (string) $attributes['variant'] : 'header';
if ( 'drawer' !== $variant ) {
	$variant = 'header';
}

$support_raw = isset( $attributes['supportUrl'] ) ? (string) $attributes['supportUrl'] : '/soutenir/';

return 'drawer' === $variant
	? jardin_get_header_utilities_drawer_markup( $support_raw )
	: jardin_get_header_utilities_header_markup( $support_raw );
