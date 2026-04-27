<?php
/**
 * Journal hub: Query Loop tweaks, ?kind= filters, and mixed-hub SQL.
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Post types aggregated on the /journal/ hub (raw listens and raw tastings removed via SQL for “All”).
 *
 * @return list<string>
 */
function jardin_get_hub_post_types(): array {
	/**
	 * Filter: jardin_hub_post_types
	 *
	 * @param list<string> $types Public post type names in the journal mixed view.
	 */
	$types = (array) apply_filters(
		'jardin_hub_post_types',
		array(
			'post',
			'iwcpt_note',
			'iwcpt_like',
			'favorite',
			'event',
			'beer_checkin',
			'listen',
		)
	);

	$out = array_values( array_unique( array_map( 'sanitize_key', $types ) ) );
	sort( $out );

	return $out;
}

/**
 * Register a custom query var for the journal Query Loop.
 */
function jardin_register_journal_query_var( $vars ) {
	$vars[] = 'jardin_clauses';
	return $vars;
}
add_filter( 'query_vars', 'jardin_register_journal_query_var' );

/**
 * @param \wpdb $wpdb WordPress database object.
 * @return string
 */
function jardin_sql_post_content_is_empty( $wpdb ) {
	// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	return "( TRIM( COALESCE( {$wpdb->posts}.post_content, '' ) ) = '' )";
}

/**
 * @param \wpdb $wpdb WordPress database object.
 * @return string
 */
function jardin_sql_post_content_not_empty( $wpdb ) {
	// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	return "( TRIM( COALESCE( {$wpdb->posts}.post_content, '' ) ) != '' )";
}

/**
 * @param \WP_Query $query Query.
 * @return bool
 */
function jardin_is_journal_mixed_query( $query ) {
	// The main site feed reuses the same post-type set; raw listen/tasting trimming is in feed-filters.
	if ( $query->is_feed() ) {
		return false;
	}
	$jc = (string) $query->get( 'jardin_clauses' );
	if ( 'mixed' === $jc ) {
		return true;
	}
	$pt = (array) $query->get( 'post_type' );
	$pt = array_values( array_unique( array_map( 'sanitize_key', $pt ) ) );
	sort( $pt );
	return ( '' === $jc && $pt === jardin_get_hub_post_types() );
}

/**
 * Restrict mixed hub: drop raw scrobbles and raw tastings; keep other CPT rows.
 *
 * @param string    $clauses Clauses.
 * @param \WP_Query $query   Query.
 * @return string
 */
function jardin_journal_clauses_mixed( $clauses, $query ) {
	if ( ! jardin_is_journal_mixed_query( $query ) ) {
		return $clauses;
	}

	global $wpdb;
	$filled = jardin_sql_post_content_not_empty( $wpdb );
	$clauses['where'] .= " AND ( ( {$wpdb->posts}.post_type != 'listen' ) OR ( {$wpdb->posts}.post_type = 'listen' AND {$filled} ) )";
	$clauses['where'] .= " AND ( ( {$wpdb->posts}.post_type != 'beer_checkin' ) OR ( {$wpdb->posts}.post_type = 'beer_checkin' AND {$filled} ) )";
	return $clauses;
}

/**
 * Bookmark: favorite, empty content.
 *
 * @param string    $clauses Clauses.
 * @param \WP_Query $query   Query.
 * @return string
 */
function jardin_journal_clauses_bookmark( $clauses, $query ) {
	if ( 'bookmark' !== (string) $query->get( 'jardin_clauses' ) ) {
		return $clauses;
	}
	global $wpdb;
	$empty = jardin_sql_post_content_is_empty( $wpdb );
	$clauses['where'] .= " AND {$empty}";
	return $clauses;
}

/**
 * Quote: favorite, content present.
 *
 * @param string    $clauses Clauses.
 * @param \WP_Query $query   Query.
 * @return string
 */
function jardin_journal_clauses_quote( $clauses, $query ) {
	if ( 'quote' !== (string) $query->get( 'jardin_clauses' ) ) {
		return $clauses;
	}
	global $wpdb;
	$filled = jardin_sql_post_content_not_empty( $wpdb );
	$clauses['where'] .= " AND {$filled}";
	return $clauses;
}

/**
 * Jam: listen with content.
 *
 * @param string    $clauses Clauses.
 * @param \WP_Query $query   Query.
 * @return string
 */
function jardin_journal_clauses_jam( $clauses, $query ) {
	if ( 'jam' !== (string) $query->get( 'jardin_clauses' ) ) {
		return $clauses;
	}
	global $wpdb;
	$filled = jardin_sql_post_content_not_empty( $wpdb );
	$clauses['where'] .= " AND {$filled}";
	return $clauses;
}

/**
 * Raw listen: empty content.
 *
 * @param string    $clauses Clauses.
 * @param \WP_Query $query   Query.
 * @return string
 */
function jardin_journal_clauses_listen_raw( $clauses, $query ) {
	if ( 'listen_raw' !== (string) $query->get( 'jardin_clauses' ) ) {
		return $clauses;
	}
	global $wpdb;
	$empty = jardin_sql_post_content_is_empty( $wpdb );
	$clauses['where'] .= " AND {$empty}";
	return $clauses;
}

/**
 * Review: beer_checkin with content.
 *
 * @param string    $clauses Clauses.
 * @param \WP_Query $query   Query.
 * @return string
 */
function jardin_journal_clauses_review( $clauses, $query ) {
	if ( 'review' !== (string) $query->get( 'jardin_clauses' ) ) {
		return $clauses;
	}
	global $wpdb;
	$filled = jardin_sql_post_content_not_empty( $wpdb );
	$clauses['where'] .= " AND {$filled}";
	return $clauses;
}

/**
 * Tasting: beer_checkin, empty content.
 *
 * @param string    $clauses Clauses.
 * @param \WP_Query $query   Query.
 * @return string
 */
function jardin_journal_clauses_tasting( $clauses, $query ) {
	if ( 'tasting' !== (string) $query->get( 'jardin_clauses' ) ) {
		return $clauses;
	}
	global $wpdb;
	$empty = jardin_sql_post_content_is_empty( $wpdb );
	$clauses['where'] .= " AND {$empty}";
	return $clauses;
}
add_filter( 'posts_clauses', 'jardin_journal_clauses_mixed', 18, 2 );
add_filter( 'posts_clauses', 'jardin_journal_clauses_bookmark', 20, 2 );
add_filter( 'posts_clauses', 'jardin_journal_clauses_quote', 20, 2 );
add_filter( 'posts_clauses', 'jardin_journal_clauses_jam', 20, 2 );
add_filter( 'posts_clauses', 'jardin_journal_clauses_listen_raw', 20, 2 );
add_filter( 'posts_clauses', 'jardin_journal_clauses_review', 20, 2 );
add_filter( 'posts_clauses', 'jardin_journal_clauses_tasting', 20, 2 );

/**
 * Map ?kind= query parameter to query vars for the journal mixed Query Loop.
 *
 * @param array  $query Query vars.
 * @param string $kind  Sanitized kind slug.
 * @return array
 */
function jardin_journal_query_for_kind( array $query, string $kind ): array {
	$query['jardin_clauses'] = '';

	switch ( $kind ) {
		case 'note':
			$query['post_type']     = 'iwcpt_note';
			$query['jardin_clauses'] = 'skip';
			break;
		case 'like':
			$query['post_type']     = 'iwcpt_like';
			$query['jardin_clauses'] = 'skip';
			break;
		case 'til':
			$query['post_type']     = 'post';
			$query['category_name'] = 'til';
			$query['jardin_clauses'] = 'skip';
			break;
		case 'bookmark':
			$query['post_type']     = 'favorite';
			$query['jardin_clauses'] = 'bookmark';
			break;
		case 'quote':
			$query['post_type']     = 'favorite';
			$query['jardin_clauses'] = 'quote';
			break;
		case 'event':
			$query['post_type']     = 'event';
			$query['jardin_clauses'] = 'skip';
			break;
		case 'jam':
			$query['post_type']     = 'listen';
			$query['jardin_clauses'] = 'jam';
			break;
		case 'review':
			$query['post_type']     = 'beer_checkin';
			$query['jardin_clauses'] = 'review';
			break;
		case 'tasting':
			$query['post_type']     = 'beer_checkin';
			$query['jardin_clauses'] = 'tasting';
			break;
		case 'listen':
			$query['post_type']     = 'listen';
			$query['jardin_clauses'] = 'listen_raw';
			break;
		default:
			break;
	}

	return $query;
}

/**
 * Adjust Query Loop block queries for jardin namespaces.
 *
 * @param array     $query Parsed query vars.
 * @param \WP_Block $block Block instance.
 * @return array
 */
function jardin_query_loop_block_query_vars( array $query, $block ): array {
	if ( ! $block instanceof WP_Block ) {
		return $query;
	}

	$attrs     = is_array( $block->attributes ) ? $block->attributes : array();
	$namespace = isset( $attrs['namespace'] ) ? (string) $attrs['namespace'] : '';
	$kind      = isset( $_GET['kind'] ) ? sanitize_key( wp_unslash( $_GET['kind'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$role      = isset( $_GET['event_role'] ) ? sanitize_key( wp_unslash( $_GET['event_role'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( 'jardin/journal-mixed' === $namespace ) {
		$query = jardin_filter_hub_query( $query, $kind );
	}

	if ( 'jardin/now-updates-feed' === $namespace ) {
		$query['post_type'] = 'post';
		$query['tax_query']  = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_tax_query
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => array( 'now-updates' ),
			),
		);
	}

	if ( 'jardin/events-upcoming' === $namespace ) {
		$query['post_type']  = 'event';
		$query['meta_key']  = 'event_date';
		$query['orderby']   = 'meta_value';
		$query['order']     = 'ASC';
		$query['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_query
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
		$query['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_query_meta_query
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
 * Apply post types and mixed/kind query flags for the journal mixed loop.
 *
 * @param array  $query Block query vars.
 * @param string $kind  Sanitized `kind` (empty = All).
 * @return array
 */
function jardin_filter_hub_query( array $query, string $kind ): array {
	if ( $kind ) {
		$query = jardin_journal_query_for_kind( $query, $kind );
		if ( isset( $query['jardin_clauses'] ) && 'skip' === $query['jardin_clauses'] ) {
			$query['jardin_clauses'] = '';
		}
		return $query;
	}
	// All: all hub CPTs; use SQL to omit raw listen + raw tasting.
	$query['post_type']     = jardin_get_hub_post_types();
	$query['jardin_clauses'] = 'mixed';
	return $query;
}

/**
 * Markup: relative ?kind= links (same request path) for the journal page.
 *
 * @return string Raw HTML for a core/html block.
 */
function jardin_get_journal_filters_markup(): string {
	$label = esc_attr__( 'Journal filters', 'jardin' );
	$items = array(
		array( 'kind' => '', 'label' => __( 'All', 'jardin' ) ),
		array( 'kind' => 'note', 'label' => __( 'Notes', 'jardin' ) ),
		array( 'kind' => 'like', 'label' => __( 'Likes', 'jardin' ) ),
		array( 'kind' => 'til', 'label' => __( 'TIL', 'jardin' ) ),
		array( 'kind' => 'bookmark', 'label' => __( 'Bookmarks', 'jardin' ) ),
		array( 'kind' => 'quote', 'label' => __( 'Quotes', 'jardin' ) ),
		array( 'kind' => 'event', 'label' => __( 'Events', 'jardin' ) ),
		array( 'kind' => 'review', 'label' => __( 'Reviews', 'jardin' ) ),
		array( 'kind' => 'tasting', 'label' => __( 'Tastings', 'jardin' ) ),
		array( 'kind' => 'jam', 'label' => __( 'Jams', 'jardin' ) ),
		array( 'kind' => 'listen', 'label' => __( 'Listens (raw)', 'jardin' ) ),
	);

	$base_no_kind = remove_query_arg( 'kind' );
	$parts        = array();
	$i            = 0;
	foreach ( $items as $item ) {
		$k   = (string) $item['kind'];
		$href = '' === $k ? $base_no_kind : add_query_arg( 'kind', $k, $base_no_kind );
		if ( $i > 0 ) {
			$parts[] = '<span class="jardin-journal-filters__sep" aria-hidden="true"> · </span>';
		}
		++$i;
		$parts[] = sprintf(
			'<a class="jardin-journal-filters__link" href="%1$s">%2$s</a>',
			esc_url( $href ),
			esc_html( (string) $item['label'] )
		);
	}

	$inner = implode( '', $parts );

	return sprintf(
		'<nav class="jardin-journal-filters" aria-label="%1$s"><p class="jardin-journal-filters__inner has-text-muted-color has-sm-font-size">%2$s</p></nav>',
		$label,
		$inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — built from esc_url/esc_html above.
	);
}
