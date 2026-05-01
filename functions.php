<?php
/**
 * jardin-theme bootstrap.
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

$jardin_inc = get_template_directory() . '/inc/';

require_once $jardin_inc . 'setup.php';
require_once $jardin_inc . 'header-toolbar.php';
require_once $jardin_inc . 'header-template-fallback.php';
require_once $jardin_inc . 'blocks.php';
require_once $jardin_inc . 'placeholder-copy.php';
require_once $jardin_inc . 'enqueue.php';
require_once $jardin_inc . 'performance.php';
require_once $jardin_inc . 'block-styles.php';
require_once $jardin_inc . 'block-variations.php';
require_once $jardin_inc . 'entry-attributes.php';
require_once $jardin_inc . 'activity-hub.php';
require_once $jardin_inc . 'hub-cpt-request.php';
require_once $jardin_inc . 'page-template-urls.php';
require_once $jardin_inc . 'hub-urls.php';
require_once $jardin_inc . 'footer-secondary-urls.php';
require_once $jardin_inc . 'notes-archive.php';
require_once $jardin_inc . 'journal-hub.php';
require_once $jardin_inc . 'feed-filters.php';
require_once $jardin_inc . 'microformats.php';
require_once $jardin_inc . 'breadcrumbs.php';
require_once $jardin_inc . 'syndication-config.php';
require_once $jardin_inc . 'rewrite-rules.php';
require_once $jardin_inc . 'class-styleguide.php';
