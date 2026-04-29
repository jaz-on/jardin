<?php
/**
 * Server output for jardin-theme/copyright.
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

$year = (int) gmdate( 'Y' );
$text = sprintf(
	/* translators: 1: four-digit year, 2: site name */
	__( '© %1$s %2$s', 'jardin-theme' ),
	(string) $year,
	get_bloginfo( 'name' )
);

return '<p class="has-text-align-center has-text-muted-color has-text-color has-sm-font-size">' . esc_html( $text ) . '</p>';
