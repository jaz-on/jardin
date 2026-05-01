<?php
/**
 * Polylang blocks register with block API v1; WordPress 6.9+ deprecates that for iframe editors.
 * Bump api_version when Polylang registers its blocks (no upstream change required in the plugin files).
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Polylang reads apiVersion from block.json (v1). Bump before registration.
 *
 * @param array<string,mixed> $metadata Parsed block.json metadata.
 * @return array<string,mixed>
 */
function jardin_polylang_block_type_metadata_api( array $metadata ): array {
	if ( empty( $metadata['name'] ) ) {
		return $metadata;
	}
	$polylang_blocks = array(
		'polylang/language-switcher',
		'polylang/navigation-language-switcher',
	);
	if ( in_array( $metadata['name'], $polylang_blocks, true ) ) {
		$metadata['apiVersion'] = 3;
	}
	return $metadata;
}
add_filter( 'block_type_metadata', 'jardin_polylang_block_type_metadata_api', 11, 1 );

/**
 * Fallback / merge when blocks register without going through block.json only.
 *
 * @param array<string,mixed> $args       Block type args.
 * @param string              $block_name Block name including namespace.
 * @return array<string,mixed>
 */
function jardin_polylang_bump_block_api_version( array $args, string $block_name ): array {
	$polylang_blocks = array(
		'polylang/language-switcher',
		'polylang/navigation-language-switcher',
	);
	if ( in_array( $block_name, $polylang_blocks, true ) ) {
		$args['api_version'] = 3;
		$args['apiVersion']   = 3;
	}
	return $args;
}
add_filter( 'register_block_type_args', 'jardin_polylang_bump_block_api_version', PHP_INT_MAX, 2 );
