<?php
/**
 * Optional rewrite rules (now-updates monthly URLs).
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register rewrite rules from jardin-docs integration/permalinks-rewrites.md.
 */
function jardin_register_rewrite_rules(): void {
	add_rewrite_rule(
		'^now-updates/([0-9]{4}-[0-9]{2})/?$',
		'index.php?category_name=now-updates&name=$matches[1]',
		'top'
	);
}
add_action( 'init', 'jardin_register_rewrite_rules' );

/**
 * Flush rewrite rules when switching to this theme.
 */
function jardin_flush_rewrites_on_switch(): void {
	flush_rewrite_rules( false );
}
add_action( 'after_switch_theme', 'jardin_flush_rewrites_on_switch' );
