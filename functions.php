<?php
/**
 * jardin-theme bootstrap.
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

$jardin_inc = get_template_directory() . '/inc/';

require_once $jardin_inc . 'content-migration.php';
require_once $jardin_inc . 'setup.php';
require_once $jardin_inc . 'blocks.php';
require_once $jardin_inc . 'placeholder-copy.php';
require_once $jardin_inc . 'search-block-i18n.php';
require_once $jardin_inc . 'enqueue.php';
require_once $jardin_inc . 'performance.php';
require_once $jardin_inc . 'block-styles.php';
require_once $jardin_inc . 'block-variations.php';
require_once $jardin_inc . 'entry-attributes.php';
require_once $jardin_inc . 'activity-hub.php';
require_once $jardin_inc . 'hub-cpt-request.php';
require_once $jardin_inc . 'notes-archive.php';
require_once $jardin_inc . 'journal-hub.php';
require_once $jardin_inc . 'project-query-guard.php';
require_once $jardin_inc . 'project-helpers.php';
require_once $jardin_inc . 'class-projects-sync.php';
require_once $jardin_inc . 'projects.php';
require_once $jardin_inc . 'projects-admin.php';
require_once $jardin_inc . 'dlc-page.php';
require_once $jardin_inc . 'feed-filters.php';
require_once $jardin_inc . 'microformats.php';
require_once $jardin_inc . 'breadcrumbs.php';
require_once $jardin_inc . 'syndication-config.php';
require_once $jardin_inc . 'rewrite-rules.php';
require_once $jardin_inc . 'now-updates.php';
require_once $jardin_inc . 'sitemap.php';
require_once $jardin_inc . 'class-styleguide.php';
