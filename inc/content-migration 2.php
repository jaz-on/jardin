<?php
/**
 * One-shot content migration: theme block/pattern/query namespaces jardin/* → jardin-theme/*.
 *
 * Runs once per bump of JARDIN_THEME_CONTENT_MIGRATION_VERSION on after_setup_theme.
 * Does not alter jardin-scrobbler blocks (wp:jardin/lastfm-*, wp:jardin/recent-*).
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/** Increment when migration map changes. */
const JARDIN_THEME_CONTENT_MIGRATION_VERSION = 3;

/**
 * Ordered (from, to) pairs for SQL REPLACE on long text fields.
 *
 * @return array<int, array{0: string, 1: string}>
 */
function jardin_theme_content_migration_pairs(): array {
	return array(
		// Theme-owned dynamic blocks (serialized markup).
		array( '<!-- wp:jardin/copyright', '<!-- wp:jardin-theme/copyright' ),
		array( '<!-- wp:jardin/post-engage', '<!-- wp:jardin-theme/post-engage' ),
		array( '<!-- wp:jardin/theme-toggle', '<!-- wp:jardin-theme/theme-toggle' ),
		array( '<!-- wp:jardin/event-link-banner', '<!-- wp:jardin-theme/event-link-banner' ),
		// Pattern slugs + Query Loop namespaces in JSON attributes.
		array( '"slug":"jardin/', '"slug":"jardin-theme/' ),
		array( '"namespace":"jardin/', '"namespace":"jardin-theme/' ),
		// Styleguide shortcode.
		array( '[jardin_styleguide]', '[jardin_theme_styleguide]' ),
	);
}

/**
 * Run REPLACE chain on a single DB column (table.column).
 *
 * @param string $table  SQL table name (e.g. wp_posts).
 * @param string $column Column name.
 */
function jardin_theme_migration_replace_column( string $table, string $column ): void {
	global $wpdb;
	$pairs = jardin_theme_content_migration_pairs();
	foreach ( $pairs as $pair ) {
		$from = $pair[0];
		$to   = $pair[1];
		$like = '%' . $wpdb->esc_like( $from ) . '%';
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- table/column are fixed internal names.
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$table} SET {$column} = REPLACE({$column}, %s, %s) WHERE {$column} LIKE %s",
				$from,
				$to,
				$like
			)
		);
	}
}

/**
 * Migrate posts, revisions, and relevant meta/options rows.
 */
function jardin_theme_run_content_namespace_migration(): void {
	if ( (int) get_option( 'jardin_theme_content_migration_version', 0 ) >= JARDIN_THEME_CONTENT_MIGRATION_VERSION ) {
		return;
	}

	global $wpdb;

	// Only plain-text columns (block markup). Avoid wp_options / postmeta: many values are PHP-serialized; changing string length corrupts them.
	jardin_theme_migration_replace_column( $wpdb->posts, 'post_content' );
	jardin_theme_migration_replace_column( $wpdb->posts, 'post_excerpt' );

	update_option( 'jardin_theme_content_migration_version', JARDIN_THEME_CONTENT_MIGRATION_VERSION, false );

	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();
	}
}
add_action( 'after_setup_theme', 'jardin_theme_run_content_namespace_migration', 1 );
