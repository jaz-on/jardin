<?php
/**
 * Title: Header — brand, toolbar, navigation
 * Slug: jardin-theme/header-main
 * Categories: header
 * Description: Header content (brand row + toolbar, then nav) — same approach as jardin-theme/footer-main. See mockup.html header.site ~1005–1133.
 * Inserter: no
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

$activity_url   = function_exists( 'jardin_get_activity_archive_url' ) ? jardin_get_activity_archive_url() : home_url( '/activites/' );
$activity_label = '/' . ( function_exists( 'jardin_get_activity_path_segment' ) ? jardin_get_activity_path_segment() : 'activites' );

$projects_url   = function_exists( 'jardin_projects_hub_url' ) ? jardin_projects_hub_url() : home_url( '/projets/' );
$projects_label = function_exists( 'jardin_projects_hub_label' ) ? jardin_projects_hub_label() : '/projets';
$now_url        = function_exists( 'jardin_now_hub_url' ) ? jardin_now_hub_url() : home_url( '/maintenant/' );
$now_label      = function_exists( 'jardin_now_hub_label' ) ? jardin_now_hub_label() : '/maintenant';

?>
<!-- wp:group {"className":"site-row site-row-brand","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|4"}}} -->
<div class="wp-block-group site-row site-row-brand">
	<!-- wp:pattern {"slug":"jardin-theme/site-brand"} /-->

	<!-- wp:pattern {"slug":"jardin-theme/site-toolbar"} /-->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"site-row site-row-nav","layout":{"type":"flex","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-group site-row site-row-nav">
	<!-- wp:navigation {"className":"primary jardin-theme-primary-nav","overlayMenu":"never","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"left","orientation":"horizontal","flexWrap":"nowrap"}} -->
		<!-- wp:navigation-link {"label":"/journal","type":"custom","url":"/journal/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"/articles","type":"custom","url":"/articles/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"<?php echo esc_attr( $activity_label ); ?>","type":"custom","url":"<?php echo esc_url( $activity_url ); ?>","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"/evenements","type":"custom","url":"/evenements/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"<?php echo esc_attr( $projects_label ); ?>","type":"custom","url":"<?php echo esc_url( $projects_url ); ?>","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"<?php echo esc_attr( $now_label ); ?>","type":"custom","url":"<?php echo esc_url( $now_url ); ?>","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"/a-propos","type":"custom","url":"/a-propos/","kind":"custom"} /-->
	<!-- /wp:navigation -->

	<?php
	if ( function_exists( 'jardin_is_header_utilities_block_registered' ) && jardin_is_header_utilities_block_registered() ) {
		echo "\n\t<!-- wp:jardin-theme/header-utilities {\"variant\":\"drawer\"} /-->\n";
	} elseif ( function_exists( 'jardin_get_header_utilities_drawer_markup' ) ) {
		echo "\n\t<!-- wp:html -->";
		echo jardin_get_header_utilities_drawer_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo "\n\t<!-- /wp:html -->\n";
	}
	?>
</div>
<!-- /wp:group -->
