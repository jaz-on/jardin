<?php
/**
 * Jardin theme bootstrap.
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

$jardin_inc = get_template_directory() . '/inc/';

require_once $jardin_inc . 'setup.php';
require_once $jardin_inc . 'enqueue.php';
require_once $jardin_inc . 'block-styles.php';
require_once $jardin_inc . 'block-variations.php';
require_once $jardin_inc . 'journal-hub.php';
require_once $jardin_inc . 'feed-filters.php';
require_once $jardin_inc . 'microformats.php';
require_once $jardin_inc . 'syndication-config.php';
require_once $jardin_inc . 'rewrite-rules.php';
require_once $jardin_inc . 'sitemap.php';
require_once $jardin_inc . 'page-seed.php';

if ( is_admin() ) {
	require_once $jardin_inc . 'admin-page-seed.php';
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once $jardin_inc . 'cli-page-seed.php';
}
