<?php
/**
 * Main + dedicated feeds: CPT mix, exclusions, raw listen/tasting handling.
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Post types included in the main site RSS/Atom feed. Raw listens and tastings
 * (empty `post_content`) are removed via SQL. Blogroll is not included.
 *
 * @return list<string>
 */
function jardin_get_main_feed_post_types(): array {
	$types = (array) apply_filters( 'jardin_main_feed_post_types', jardin_get_hub_post_types() );
	$out   = array_values( array_unique( array_map( 'sanitize_key', $types ) ) );
	sort( $out );
	return $out;
}

/**
 * Let the theme’s feed query keep the `listen` CPT so we can still publish *jams*
 * (non-empty content). jardin-scrobble would otherwise drop every listen from mixed feeds.
 */
add_filter( 'jardin_scrobble_exclude_from_main_feed', '__return_false' );

/**
 * Main feed: multi-CPT, exclude `now-updates` posts (native category), and flag for SQL.
 *
 * @param \WP_Query $query Main query.
 */
function jardin_feed_main_pre_get_posts( $query ): void {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_feed() || $query->is_comment_feed() ) {
		return;
	}
	if ( in_array( (string) $query->get( 'feed' ), array( 'listens' ), true ) || $query->is_feed( 'listens' ) ) {
		return;
	}
	$query->set( 'post_type', jardin_get_main_feed_post_types() );
	$now = get_term_by( 'slug', 'now-updates', 'category' );
	if ( $now && ! is_wp_error( $now ) ) {
		$query->set( 'category__not_in', array( (int) $now->term_id ) );
	}
	$query->set( 'jardin_clauses', 'main_feed' );
}
add_action( 'pre_get_posts', 'jardin_feed_main_pre_get_posts', 3 );

/**
 * Remove raw scrobbles and raw tastings from the main feed; keep everything else in the query.
 *
 * @param string    $clauses Clauses.
 * @param \WP_Query $q       Query.
 * @return string
 */
function jardin_feed_raw_content_clauses( $clauses, $q ) {
	if ( ! $q->is_main_query() || ! $q->is_feed() || $q->is_comment_feed() ) {
		return $clauses;
	}
	if ( 'listens' === (string) $q->get( 'feed' ) || $q->is_feed( 'listens' ) ) {
		return $clauses;
	}
	$flag = (string) $q->get( 'jardin_clauses' );
	$pt   = (array) $q->get( 'post_type' );
	$pt   = array_values( array_unique( array_map( 'sanitize_key', $pt ) ) );
	sort( $pt );
	$exp = jardin_get_main_feed_post_types();
	// Accept either the explicit feed flag (when query var survives) or the configured post type set.
	if ( 'main_feed' !== $flag && $pt !== $exp ) {
		return $clauses;
	}

	global $wpdb;
	$empty  = jardin_sql_post_content_is_empty( $wpdb );
	$filled = jardin_sql_post_content_not_empty( $wpdb );
	$clauses['where'] .= " AND ( ( {$wpdb->posts}.post_type != 'listen' ) OR ( {$wpdb->posts}.post_type = 'listen' AND {$filled} ) )";
	$clauses['where'] .= " AND ( ( {$wpdb->posts}.post_type != 'beer_checkin' ) OR ( {$wpdb->posts}.post_type = 'beer_checkin' AND {$filled} ) )";
	return $clauses;
}
add_filter( 'posts_clauses', 'jardin_feed_raw_content_clauses', 24, 2 );
