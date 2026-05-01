<?php
/**
 * Title: Site toolbar
 * Slug: jardin-theme/site-toolbar
 * Categories: hidden
 * Description: Header utilities — language, burger, search, theme, music, support. Prefers the `header-utilities` block; falls back to core/html if the block is not registered (incomplete deploy).
 * Inserter: no
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'jardin_is_header_utilities_block_registered' ) && jardin_is_header_utilities_block_registered() ) {
	echo '<!-- wp:jardin-theme/header-utilities {"variant":"header"} /-->';
} elseif ( function_exists( 'jardin_get_header_utilities_header_markup' ) ) {
	echo '<!-- wp:html -->';
	echo jardin_get_header_utilities_header_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '<!-- /wp:html -->';
}
