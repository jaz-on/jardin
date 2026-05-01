<?php
/**
 * Server output for jardin-theme/header-utilities.
 *
 * @package Jardin_Theme
 * @var array<string, mixed> $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$variant = isset( $attributes['variant'] ) && 'drawer' === $attributes['variant'] ? 'drawer' : 'header';

return 'drawer' === $variant
	? jardin_get_header_utilities_drawer_markup()
	: jardin_get_header_utilities_header_markup();
