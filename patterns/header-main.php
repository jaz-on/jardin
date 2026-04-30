<?php
/**
 * Title: Header — marque, toolbar, navigation
 * Slug: jardin-theme/header-main
 * Categories: header
 * Description: Contenu du header (ligne brand + toolbar, puis nav) — même principe que jardin-theme/footer-main. Voir mockup.html header.site ~1005–1133.
 * Inserter: no
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

$activity_url   = function_exists( 'jardin_get_activity_archive_url' ) ? jardin_get_activity_archive_url() : home_url( '/activites/' );
$activity_label = '/' . ( function_exists( 'jardin_get_activity_path_segment' ) ? jardin_get_activity_path_segment() : 'activites' );
$search_url     = home_url( '/?s=' );
$support_url    = home_url( '/soutenir/' );
$svg_search = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>';
$svg_music  = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>';
$svg_coffee = '<svg class="coffee-icon coffee-icon-coffee" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 2v2"/><path d="M14 2v2"/><path d="M16 8a1 1 0 0 1 1 1v8a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V9a1 1 0 0 1 1-1h14a4 4 0 1 1 0 8h-1"/><path d="M6 2v2"/></svg>';
$svg_cherry = '<svg class="coffee-icon coffee-icon-cherry" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 17a5 5 0 0 0 10 0c0-2.76-2.5-5-5-3-2.5-2-5 .24-5 3Z"/><path d="M12 17a5 5 0 0 0 10 0c0-2.76-2.5-5-5-3-2.5-2-5 .24-5 3Z"/><path d="M7 14c3.22-2.91 4.29-8.75 5-12 1.66 2.38 4.94 9 5 12"/><path d="M22 9c-4.29 0-7.14-2.33-7-7-3 0-4.5 2-4.5 5"/></svg>';
$svg_beer   = '<svg class="coffee-icon coffee-icon-beer" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 11h1a3 3 0 0 1 0 6h-1"/><path d="M9 12v6"/><path d="M13 12v6"/><path d="M14 7.5c-1 0-1.44.5-3 .5s-2-.5-3-.5-1.72.5-2.5.5a2.5 2.5 0 0 1 0-5c.78 0 1.57.5 2.5.5C9.44 3.5 10 3 11 3s1.44.5 3 .5 2.5-.5 2.5-.5a2.5 2.5 0 0 1 0 5c-.78 0-1.5-.5-2.5-.5Z"/><path d="M5 8v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V8"/></svg>';

?>
<!-- wp:group {"className":"site-row site-row-brand","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|4"}}} -->
<div class="wp-block-group site-row site-row-brand">
	<!-- wp:pattern {"slug":"jardin-theme/site-brand"} /-->

	<!-- wp:pattern {"slug":"jardin-theme/site-toolbar"} /-->
</div>
<!-- /wp:group -->

<?php if ( function_exists( 'jardin_render_breadcrumb' ) ) : ?>
	<!-- wp:html -->
	<?php echo jardin_render_breadcrumb(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<!-- /wp:html -->
<?php endif; ?>

<!-- wp:group {"className":"site-row site-row-nav","layout":{"type":"flex","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-group site-row site-row-nav">
	<!-- wp:navigation {"className":"primary jardin-theme-primary-nav","overlayMenu":"never","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"left","orientation":"horizontal","flexWrap":"nowrap"}} -->
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
			<a class="icon-btn"
			   href="<?php echo esc_url( $search_url ); ?>"
			   aria-label="<?php echo esc_attr__( 'Rechercher', 'jardin-theme' ); ?>"
			   rel="search"
			><?php echo $svg_search; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>

			<?php echo do_blocks( '<!-- wp:jardin-theme/theme-toggle /-->' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			<button class="icon-btn lfm-toolbar-toggle"
			        type="button"
			        aria-label="<?php echo esc_attr__( 'Musique en cours', 'jardin-theme' ); ?>"
			        title="<?php echo esc_attr__( 'Musique en cours', 'jardin-theme' ); ?>"
			        aria-pressed="false"
			><?php echo $svg_music; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>

			<a class="icon-btn coffee-toggle"
			   href="<?php echo esc_url( $support_url ); ?>"
			   aria-label="<?php echo esc_attr__( 'Me soutenir', 'jardin-theme' ); ?>"
			   title="<?php echo esc_attr__( 'Me soutenir', 'jardin-theme' ); ?>"
			><?php echo $svg_coffee . $svg_cherry . $svg_beer; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		</div>
	</div>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->
