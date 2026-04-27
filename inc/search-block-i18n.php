<?php
/**
 * Localize core/search block attributes at render time (FSE HTML stores literal strings).
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Force translated labels for the search block (templates + patterns).
 *
 * @param array<string,mixed> $parsed_block Parsed block data.
 * @return array<string,mixed>
 */
function jardin_i18n_search_block_data( array $parsed_block ): array {
	if ( ( $parsed_block['blockName'] ?? '' ) !== 'core/search' ) {
		return $parsed_block;
	}
	if ( ! isset( $parsed_block['attrs'] ) || ! is_array( $parsed_block['attrs'] ) ) {
		$parsed_block['attrs'] = array();
	}
	$parsed_block['attrs']['label']       = __( 'Search', 'jardin' );
	$parsed_block['attrs']['placeholder'] = __( 'Search…', 'jardin' );
	$parsed_block['attrs']['buttonText']  = __( 'Search', 'jardin' );
	return $parsed_block;
}
add_filter( 'render_block_data', 'jardin_i18n_search_block_data', 10, 1 );
