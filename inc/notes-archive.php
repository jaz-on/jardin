<?php
/**
 * Notes archive: optional ?note_kind= filter when taxonomy exists (IndieBlocks).
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/**
 * Restrict `iwcpt_note` archive when `note_kind` taxonomy exists and GET param is set.
 *
 * @param \WP_Query $query Main query.
 */
function jardin_pre_get_posts_notes_archive_kind( $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( ! $query->is_post_type_archive( 'iwcpt_note' ) ) {
		return;
	}

	$tax = jardin_get_note_kind_taxonomy_slug();
	if ( '' === $tax ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- public archive facet.
	$slug = isset( $_GET['note_kind'] ) ? sanitize_key( wp_unslash( $_GET['note_kind'] ) ) : '';
	if ( '' === $slug ) {
		return;
	}

	$term = get_term_by( 'slug', $slug, $tax );
	if ( ! $term || is_wp_error( $term ) ) {
		return;
	}

	$tq = $query->get( 'tax_query' );
	$tq = is_array( $tq ) ? $tq : array();
	$tq[] = array(
		'taxonomy' => $tax,
		'field'    => 'slug',
		'terms'    => array( $slug ),
	);
	$query->set( 'tax_query', $tq ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_tax_query
}
add_action( 'pre_get_posts', 'jardin_pre_get_posts_notes_archive_kind', 11 );
