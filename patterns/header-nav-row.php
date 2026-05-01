<?php
/**
 * Title: Header — nav row (primary nav + mobile drawer tools)
 * Slug: jardin-theme/header-nav-row
 * Categories: header
 * Description: Primary Navigation (starter links) and drawer utilities — block markup lives in `patterns/includes/header-nav-row.markup.html`; edit there or in Site Editor after inserting.
 * Inserter: no
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

$path = __DIR__ . '/includes/header-nav-row.markup.html';
if ( is_readable( $path ) ) {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme-owned serialized block markup.
	echo file_get_contents( $path );
}
