<?php
/**
 * Title: Header — marque, toolbar, navigation
 * Slug: jardin/header-main
 * Categories: header
 * Description: Contenu du header (ligne brand + toolbar, puis nav) — même principe que jardin/footer-main. Voir mockup.html header.site ~1005–1133.
 * Inserter: no
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

$activity_url   = function_exists( 'jardin_get_activity_archive_url' ) ? jardin_get_activity_archive_url() : home_url( '/activite/' );
$activity_label = '/' . ( function_exists( 'jardin_get_activity_path_segment' ) ? jardin_get_activity_path_segment() : 'activite' );

?>
<!-- wp:group {"className":"site-row site-row-brand","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|4"}}} -->
<div class="wp-block-group site-row site-row-brand">
	<!-- wp:pattern {"slug":"jardin/site-brand"} /-->

	<!-- wp:pattern {"slug":"jardin/site-toolbar"} /-->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"site-row site-row-nav","layout":{"type":"flex","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-group site-row site-row-nav">
	<!-- wp:navigation {"className":"primary jardin-primary-nav","overlayMenu":"never","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"left","orientation":"horizontal","flexWrap":"nowrap"}} -->
		<!-- wp:navigation-link {"label":"/journal","type":"custom","url":"/journal/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"/articles","type":"custom","url":"/articles/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"<?php echo esc_attr( $activity_label ); ?>","type":"custom","url":"<?php echo esc_url( $activity_url ); ?>","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"/evenements","type":"custom","url":"/evenements/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"/projets","type":"custom","url":"/projets/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"/maintenant","type":"custom","url":"/maintenant/","kind":"custom"} /-->
		<!-- wp:navigation-link {"label":"/a-propos","type":"custom","url":"/a-propos/","kind":"custom"} /-->
	<!-- /wp:navigation -->

	<!-- wp:html -->
	<div class="site-nav-drawer-tools" role="region" aria-labelledby="site-nav-drawer-tools-heading">
		<p class="site-nav-drawer-tools__heading" id="site-nav-drawer-tools-heading"><?php echo esc_html__( 'Outils', 'jardin-theme' ); ?></p>
		<div class="toolbar-chrome toolbar-chrome--drawer" role="group" aria-label="<?php echo esc_attr__( 'Recherche, thème, musique et soutien', 'jardin-theme' ); ?>">
			<?php echo jardin_render_toolbar_chrome(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->
