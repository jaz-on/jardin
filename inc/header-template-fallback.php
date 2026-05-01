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
 * Log header fallback decisions (debug).
 *
 * Enable either:
 * - `define( 'JARDIN_DEBUG_HEADER_FALLBACK', true );` in `wp-config.php` (requires `WP_DEBUG_LOG` or server logging), or
 * - `add_filter( 'jardin_header_template_fallback_debug', '__return_true' );` in a small MU-plugin / Code Snippets.
 *
 * Lines are prefixed with `[jardin header]` and JSON-encoded context.
 *
 * @param array<string, mixed> $context Context keys for support.
 */
function jardin_header_template_fallback_debug_log( array $context ): void {
	$enabled = ( defined( 'JARDIN_DEBUG_HEADER_FALLBACK' ) && JARDIN_DEBUG_HEADER_FALLBACK )
		|| (bool) apply_filters( 'jardin_header_template_fallback_debug', false );
	if ( ! $enabled ) {
		return;
	}
	// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	error_log( '[jardin header] ' . wp_json_encode( $context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
}

/**
 * Markers (legacy string check) — kept for filters and tooling; structure completeness uses
 * {@see jardin_header_template_structure_is_complete()} instead.
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
 * Scan parsed blocks for toolbar blocks and default header pattern references.
 *
 * @param array<int, array<string, mixed>> $blocks Top-level or inner `parse_blocks()` output.
 * @return array{utilities:int,has_brand_row_pattern:bool,has_nav_row_pattern:bool,has_main_pattern:bool,has_site_toolbar_pattern:bool}
 */
function jardin_header_template_scan_blocks( array $blocks ): array {
	$out = array(
		'utilities'                 => 0,
		'has_brand_row_pattern'     => false,
		'has_nav_row_pattern'       => false,
		'has_main_pattern'          => false,
		'has_site_toolbar_pattern'  => false,
	);
	foreach ( $blocks as $block ) {
		if ( ! is_array( $block ) || empty( $block['blockName'] ) ) {
			continue;
		}
		$name = (string) $block['blockName'];
		if ( 'jardin-theme/header-utilities' === $name ) {
			++$out['utilities'];
		} elseif ( 'core/pattern' === $name ) {
			$slug = isset( $block['attrs']['slug'] ) ? (string) $block['attrs']['slug'] : '';
			if ( 'jardin-theme/header-brand-row' === $slug ) {
				$out['has_brand_row_pattern'] = true;
			} elseif ( 'jardin-theme/header-nav-row' === $slug ) {
				$out['has_nav_row_pattern'] = true;
			} elseif ( 'jardin-theme/header-main' === $slug ) {
				$out['has_main_pattern'] = true;
			} elseif ( 'jardin-theme/site-toolbar' === $slug ) {
				$out['has_site_toolbar_pattern'] = true;
			}
		}
		if ( ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
			$inner = jardin_header_template_scan_blocks( $block['innerBlocks'] );
			$out['utilities']              += $inner['utilities'];
			$out['has_brand_row_pattern']     = $out['has_brand_row_pattern'] || $inner['has_brand_row_pattern'];
			$out['has_nav_row_pattern']       = $out['has_nav_row_pattern'] || $inner['has_nav_row_pattern'];
			$out['has_main_pattern']          = $out['has_main_pattern'] || $inner['has_main_pattern'];
			$out['has_site_toolbar_pattern']  = $out['has_site_toolbar_pattern'] || $inner['has_site_toolbar_pattern'];
		}
	}
	return $out;
}

/**
 * Whether saved header markup still includes the dynamic utilities (or the theme pattern bundle that provides them).
 *
 * Previously any substring match (e.g. `header-nav-row` alone) treated the part as "complete", so a customized
 * header without `jardin-theme/header-utilities` never fell back to `parts/header.html` and the toolbar disappeared.
 *
 * A customized header (`WP_Block_Template::$source` `custom`) can still reference `header-brand-row` + `header-nav-row`
 * while synced/copied patterns in the DB omit the utilities block — so the pattern pair alone is only trusted when
 * the template part is not a DB customization (`source` `custom`), where synced patterns may omit utilities.
 *
 * @param string $content          Raw block markup from the template part.
 * @param string $template_source  `WP_Block_Template::$source` (e.g. `theme`, `custom`, `plugin`).
 * @return bool
 */
function jardin_header_template_structure_is_complete( string $content, string $template_source = '' ): bool {
	$content = trim( $content );
	if ( '' === $content ) {
		return false;
	}
	$blocks = parse_blocks( $content );
	if ( ! is_array( $blocks ) ) {
		return false;
	}
	$scan = jardin_header_template_scan_blocks( $blocks );
	if ( $scan['utilities'] >= 2 ) {
		return true;
	}
	if ( $scan['has_main_pattern'] ) {
		return true;
	}
	if ( $scan['has_brand_row_pattern'] && $scan['has_nav_row_pattern'] && 'custom' !== $template_source ) {
		return true;
	}
	if ( $scan['has_site_toolbar_pattern'] && $scan['has_nav_row_pattern'] ) {
		return true;
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
		jardin_header_template_fallback_debug_log( array( 'event' => 'skip_filter_disabled' ) );
		return $block_template;
	}

	if ( ! function_exists( 'get_block_file_template' ) ) {
		jardin_header_template_fallback_debug_log( array( 'event' => 'skip_no_get_block_file_template' ) );
		return $block_template;
	}

	if ( ! $block_template || ! isset( $block_template->content ) || ! is_string( $block_template->content ) ) {
		jardin_header_template_fallback_debug_log( array( 'event' => 'empty_template_using_file_only' ) );
		$file_only = get_block_file_template( $id, $template_type );
		return $file_only instanceof WP_Block_Template ? $file_only : $block_template;
	}

	$source = isset( $block_template->source ) ? (string) $block_template->source : '';
	$scan   = jardin_header_template_scan_blocks( parse_blocks( $block_template->content ) );
	$ok     = jardin_header_template_structure_is_complete( $block_template->content, $source );

	jardin_header_template_fallback_debug_log(
		array(
			'event'              => 'evaluated',
			'template_id'        => $id,
			'source'             => $source,
			'structure_complete' => $ok,
			'scan'               => $scan,
			'content_length'     => strlen( $block_template->content ),
		)
	);

	if ( $ok ) {
		return $block_template;
	}

	$file_template = get_block_file_template( $id, $template_type );
	$using_file    = $file_template instanceof WP_Block_Template;

	jardin_header_template_fallback_debug_log(
		array(
			'event'       => 'fallback',
			'using_file'  => $using_file,
			'file_source' => $using_file && isset( $file_template->source ) ? (string) $file_template->source : null,
		)
	);

	return $using_file ? $file_template : $block_template;
}

/*
 * Run late so another `get_block_template` callback cannot re-introduce an incomplete
 * header from the DB after we substitute `parts/header.html`.
 */
add_filter( 'get_block_template', 'jardin_filter_header_template_part_fallback', 999, 3 );
