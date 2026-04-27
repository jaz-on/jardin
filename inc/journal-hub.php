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
	// Phase 2: hub matches page-journal Query Loop — post, iwcpt_note, iwcpt_like only.
	// Plugin CPT kinds (bookmark, event, …) ship in Phase 4+; see integration/kinds-mapping.md.
	switch ( $kind ) {
		case 'note':
			$query['post_type'] = 'iwcpt_note';
			break;
		case 'like':
			$query['post_type'] = 'iwcpt_like';
			break;
		case 'til':
			$query['post_type']      = 'post';
			$query['category_name'] = 'til';
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
function jardin_query_loop_block_query_vars( array $query, $block ): array {
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

	// Presets for Phase 4+ (CPTs from plugins). Safe when types are absent (empty loop).
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
add_filter( 'query_loop_block_query_vars', 'jardin_query_loop_block_query_vars', 10, 2 );

/**
 * Relative filter links for the journal hub (?kind=).
 *
 * @return string Raw HTML for a core/html block (nav + links already escaped).
 */
function jardin_get_journal_filters_markup(): string {
	$label = esc_attr__( 'Journal filters', 'jardin' );
	$links = array(
		array( 'href' => '?', 'label' => __( 'All', 'jardin' ) ),
		array( 'href' => '?kind=note', 'label' => __( 'Notes', 'jardin' ) ),
		array( 'href' => '?kind=like', 'label' => __( 'Likes', 'jardin' ) ),
		array( 'href' => '?kind=til', 'label' => __( 'TIL', 'jardin' ) ),
	);

	$parts = array();
	foreach ( $links as $i => $item ) {
		if ( $i > 0 ) {
			$parts[] = '<span class="jardin-journal-filters__sep" aria-hidden="true"> · </span>';
		}
		$parts[] = sprintf(
			'<a class="jardin-journal-filters__link" href="%1$s">%2$s</a>',
			esc_url( $item['href'] ),
			esc_html( $item['label'] )
		);
	}

	$inner = implode( '', $parts );

	return sprintf(
		'<nav class="jardin-journal-filters" aria-label="%1$s"><p class="jardin-journal-filters__inner has-text-muted-color has-sm-font-size">%2$s</p></nav>',
		$label,
		$inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — built from esc_url/esc_html above.
	);
}
