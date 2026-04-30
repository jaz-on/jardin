<?php
/**
 * Guard project query loops when project CPT is unavailable.
 *
 * Prevents WordPress from falling back to generic posts when a Query Loop
 * targets post_type=project but the CPT is not registered yet.
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Force empty result set if project CPT is missing.
 *
 * @param array     $query Query vars for Query Loop block.
 * @param \WP_Block $block Block instance.
 * @param int       $page  Current page index.
 * @return array
 */
function jardin_guard_project_query_loop( array $query, $block, int $page ): array { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	$post_type = $query['post_type'] ?? '';

	$is_project_target = ( is_string( $post_type ) && 'project' === $post_type )
		|| ( is_array( $post_type ) && in_array( 'project', $post_type, true ) );

	if ( ! $is_project_target ) {
		return $query;
	}

	if ( post_type_exists( 'project' ) ) {
		return $query;
	}

	// Prevent accidental fallback to regular posts.
	$query['post_type'] = 'post';
	$query['post__in']  = array( 0 );

	return $query;
}
add_filter( 'query_loop_block_query_vars', 'jardin_guard_project_query_loop', 10, 3 );
