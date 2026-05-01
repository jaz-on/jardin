<?php
/**
 * Title: Header — nav row (primary nav + mobile drawer tools)
 * Slug: jardin-theme/header-nav-row
 * Categories: header
 * Description: Second header row: primary navigation and duplicate toolbar for the mobile drawer. Hub URLs follow PHP helpers when the pattern is rendered from disk.
 * Inserter: no
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

$activity_url   = function_exists( 'jardin_get_activity_archive_url' ) ? jardin_get_activity_archive_url() : home_url( '/activites/' );
$activity_label = '/' . ( function_exists( 'jardin_get_activity_path_segment' ) ? jardin_get_activity_path_segment() : 'activites' );

$journal_url   = function_exists( 'jardin_journal_hub_url' ) ? jardin_journal_hub_url() : home_url( '/journal/' );
$journal_label = function_exists( 'jardin_journal_hub_label' ) ? jardin_journal_hub_label() : '/journal';

$articles_url   = function_exists( 'jardin_articles_hub_url' ) ? jardin_articles_hub_url() : home_url( '/articles/' );
$articles_label = function_exists( 'jardin_articles_hub_label' ) ? jardin_articles_hub_label() : '/articles';

$events_url   = function_exists( 'jardin_get_event_archive_url' ) ? jardin_get_event_archive_url() : home_url( '/evenements/' );
$events_label = function_exists( 'jardin_get_event_archive_label' ) ? jardin_get_event_archive_label() : '/evenements';
if ( '' === $events_url ) {
	$events_url = trailingslashit( home_url( '/evenements/' ) );
}
if ( '' === $events_label ) {
	$events_label = '/evenements';
}

$projects_url   = function_exists( 'jardin_projects_hub_url' ) ? jardin_projects_hub_url() : home_url( '/projets/' );
$projects_label = function_exists( 'jardin_projects_hub_label' ) ? jardin_projects_hub_label() : '/projets';

$now_url   = function_exists( 'jardin_updates_hub_url' ) ? jardin_updates_hub_url() : home_url( '/maintenant/' );
$now_label = function_exists( 'jardin_updates_hub_label' ) ? jardin_updates_hub_label() : '/maintenant';

$about_url   = function_exists( 'jardin_about_hub_url' ) ? jardin_about_hub_url() : home_url( '/a-propos/' );
$about_label = function_exists( 'jardin_about_hub_label' ) ? jardin_about_hub_label() : '/a-propos';

?>
<!-- wp:group {"className":"site-row site-row-nav","layout":{"type":"flex","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-group site-row site-row-nav">
	<!-- wp:navigation {"className":"primary jardin-theme-primary-nav","overlayMenu":"never","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"left","orientation":"horizontal","flexWrap":"nowrap"}} -->
		<!-- wp:navigation-link {"label":"<?php echo esc_attr( $journal_label ); ?>","type":"custom","url":"<?php echo esc_url( $journal_url ); ?>","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"<?php echo esc_attr( $articles_label ); ?>","type":"custom","url":"<?php echo esc_url( $articles_url ); ?>","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"<?php echo esc_attr( $activity_label ); ?>","type":"custom","url":"<?php echo esc_url( $activity_url ); ?>","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"<?php echo esc_attr( $events_label ); ?>","type":"custom","url":"<?php echo esc_url( $events_url ); ?>","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"<?php echo esc_attr( $projects_label ); ?>","type":"custom","url":"<?php echo esc_url( $projects_url ); ?>","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"<?php echo esc_attr( $now_label ); ?>","type":"custom","url":"<?php echo esc_url( $now_url ); ?>","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"<?php echo esc_attr( $about_label ); ?>","type":"custom","url":"<?php echo esc_url( $about_url ); ?>","kind":"custom"} /-->
	<!-- /wp:navigation -->

	<!-- wp:jardin-theme/header-utilities {"variant":"drawer"} /-->
</div>
<!-- /wp:group -->
