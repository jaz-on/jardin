<?php
/**
 * When the Header template part is customized in the database but no longer
 * includes the toolbar / mobile drawer blocks, the front shows only the logo
 * and navigation can stay hidden on small screens (no burger). This filter
 * falls back to the theme file `parts/header.html` when saved markup is missing
 * the expected block or pattern references.
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Markers that must appear in saved header part content for it to be considered complete.
 *
 * @return string[]
 */
function jardin_header_template_expected_markers(): array {
	$markers = array(
		'jardin-theme/header-utilities',
		'jardin-theme/site-toolbar',
		'jardin-theme/header-main',
		'jardin-theme/header-brand-row',
		'jardin-theme/header-nav-row',
	);
	return (array) apply_filters( 'jardin_header_template_expected_markers', $markers );
}

/**
 * Whether saved template part content includes at least one expected marker.
 *
 * @param string $content Raw block markup.
 * @return bool
 */
function jardin_header_template_content_is_complete( string $content ): bool {
	foreach ( jardin_header_template_expected_markers() as $needle ) {
		if ( '' !== $needle && str_contains( $content, $needle ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Use the theme file header when the resolved template part is incomplete.
 *
 * @param WP_Block_Template|null $block_template Resolved template.
 * @param string                 $id             e.g. jardin-theme//header.
 * @param string                 $template_type  wp_template_part.
 * @return WP_Block_Template|null
 */
function jardin_filter_header_template_part_fallback( $block_template, string $id, string $template_type ) {
	if ( 'wp_template_part' !== $template_type ) {
		return $block_template;
	}

	$stylesheet = get_stylesheet();
	if ( $id !== $stylesheet . '//header' ) {
		return $block_template;
	}

	if ( ! apply_filters( 'jardin_header_template_fallback_enabled', true ) ) {
		return $block_template;
	}

	if ( ! function_exists( 'get_block_file_template' ) ) {
		return $block_template;
	}

	if ( ! $block_template || ! isset( $block_template->content ) || ! is_string( $block_template->content ) ) {
		$file_only = get_block_file_template( $id, $template_type );
		return $file_only instanceof WP_Block_Template ? $file_only : $block_template;
	}

	if ( jardin_header_template_content_is_complete( $block_template->content ) ) {
		return $block_template;
	}

	$file_template = get_block_file_template( $id, $template_type );

	return $file_template instanceof WP_Block_Template ? $file_template : $block_template;
}

add_filter( 'get_block_template', 'jardin_filter_header_template_part_fallback', 5, 3 );
