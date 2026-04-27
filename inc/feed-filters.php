<?php
/**
 * Main feed adjustments (now-updates, listens) — see jardin-docs theme/now-updates-workflow.md.
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Exclude now-updates category posts from the main site feed (optional).
 *
 * @param \WP_Query $query Main query.
 */
function jardin_feed_exclude_now_updates( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_feed() || $query->is_comment_feed() ) {
		return;
	}

	$post_type = $query->get( 'post_type' );
	if ( $post_type && 'post' !== $post_type ) {
		return;
	}

	if ( $query->get( 'category_name' ) || $query->get( 'cat' ) ) {
		return;
	}

	if ( ! empty( $query->get( 'tax_query' ) ) ) {
		return;
	}

	$tax_query   = (array) $query->get( 'tax_query' );
	$tax_query[] = array(
		'taxonomy' => 'category',
		'field'    => 'slug',
		'terms'    => array( 'now-updates' ),
		'operator' => 'NOT IN',
	);
	$query->set( 'tax_query', $tax_query );
}
add_action( 'pre_get_posts', 'jardin_feed_exclude_now_updates', 11 );
