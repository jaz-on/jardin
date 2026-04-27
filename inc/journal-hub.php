<?php
/**
 * Journal hub: Query Loop tweaks and ?kind= filters.
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Map ?kind= query parameter to query vars for the journal mixed Query Loop.
 *
 * @param array  $query Query vars.
 * @param string $kind  Sanitized kind slug.
 * @return array
 */
function jardin_journal_query_for_kind( array $query, string $kind ): array {
	switch ( $kind ) {
		case 'note':
			$query['post_type'] = 'iwcpt_note';
			break;
		case 'like':
			$query['post_type'] = 'iwcpt_like';
			break;
		case 'bookmark':
		case 'quote':
			$query['post_type'] = 'favorite';
			break;
		case 'til':
			$query['post_type']   = 'post';
			$query['category_name'] = 'til';
			break;
		case 'listen':
		case 'jam':
			$query['post_type'] = 'listen';
			break;
		case 'tasting':
		case 'review':
			$query['post_type'] = 'beer_checkin';
			break;
		case 'event':
			$query['post_type'] = 'event';
			break;
		default:
			break;
	}

	return $query;
}

/**
 * Adjust Query Loop block queries for jardin namespaces.
 *
 * @param array                   $query Parsed query vars.
 * @param \WP_Block               $block Block instance.
 * @param int                     $page  Page number.
 * @return array
 */
function jardin_query_loop_block_query_vars( array $query, $block, int $page ): array { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	if ( ! $block instanceof WP_Block ) {
		return $query;
	}

	$attrs = is_array( $block->attributes ) ? $block->attributes : array();
	$namespace   = isset( $attrs['namespace'] ) ? (string) $attrs['namespace'] : '';
	$kind        = isset( $_GET['kind'] ) ? sanitize_key( wp_unslash( $_GET['kind'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$role        = isset( $_GET['event_role'] ) ? sanitize_key( wp_unslash( $_GET['event_role'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( 'jardin/journal-mixed' === $namespace && $kind ) {
		$query = jardin_journal_query_for_kind( $query, $kind );
	}

	if ( 'jardin/now-updates-feed' === $namespace ) {
		$query['post_type'] = 'post';
		$query['tax_query']  = array(
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => array( 'now-updates' ),
			),
		);
	}

	if ( 'jardin/events-upcoming' === $namespace ) {
		$query['post_type']   = 'event';
		$query['meta_key']    = 'event_date';
		$query['orderby']     = 'meta_value';
		$query['order']       = 'ASC';
		$query['meta_query']  = array(
			array(
				'key'     => 'event_date',
				'value'   => gmdate( 'Y-m-d' ),
				'compare' => '>=',
				'type'    => 'DATE',
			),
		);
	}

	if ( 'jardin/events-past-by-role' === $namespace && $role ) {
		$query['post_type']  = 'event';
		$query['meta_query'] = array(
			array(
				'key'     => 'event_role',
				'value'   => $role,
				'compare' => 'LIKE',
			),
		);
	}

	return $query;
}
add_filter( 'query_loop_block_query_vars', 'jardin_query_loop_block_query_vars', 10, 3 );
