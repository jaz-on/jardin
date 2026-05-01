<?php
/**
 * Title: Footer — 4 colonnes + webring
 * Slug: jardin-theme/footer-main
 * Categories: footer
 * Description: Four columns with core Navigation blocks (starter links). Edit in Site Editor; webring block below.
 * Inserter: no
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

?>
<!-- wp:columns {"align":"wide","className":"cols","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|6","left":"var:preset|spacing|6"}}}} -->
<div class="wp-block-columns alignwide cols">
	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:heading {"level":4} -->
		<h4 class="wp-block-heading"><?php esc_html_e( 'Explore', 'jardin-theme' ); ?></h4>
		<!-- /wp:heading -->
		<!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","justifyContent":"left","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"0"}}} -->
			<!-- wp:navigation-link {"label":"/journal","type":"custom","url":"/journal/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"/articles","type":"custom","url":"/articles/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"/activites","type":"custom","url":"/activites/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"/evenements","type":"custom","url":"/evenements/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"/projets","type":"custom","url":"/projets/","kind":"custom"} /-->
		<!-- /wp:navigation -->
	</div>
	<!-- /wp:column -->
	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:heading {"level":4} -->
		<h4 class="wp-block-heading"><?php esc_html_e( 'More to explore', 'jardin-theme' ); ?></h4>
		<!-- /wp:heading -->
		<!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","justifyContent":"left","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"0"}}} -->
			<!-- wp:navigation-link {"label":"/maintenant","type":"custom","url":"/maintenant/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"/dlc","type":"custom","url":"/dlc/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"/toast","type":"custom","url":"/toast/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"/blogroll","type":"custom","url":"/blogroll/","kind":"custom"} /-->
		<!-- /wp:navigation -->
	</div>
	<!-- /wp:column -->
	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:heading {"level":4} -->
		<h4 class="wp-block-heading"><?php esc_html_e( 'This site', 'jardin-theme' ); ?></h4>
		<!-- /wp:heading -->
		<!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","justifyContent":"left","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"0"}}} -->
			<!-- wp:navigation-link {"label":"/index","type":"custom","url":"/index/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"/colophon","type":"custom","url":"/colophon/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"/flux","type":"custom","url":"/flux/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"/styleguide","type":"custom","url":"/styleguide/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"/ia","type":"custom","url":"/ia/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"/mentions-légales","type":"custom","url":"/mentions-legales/","kind":"custom"} /-->
		<!-- /wp:navigation -->
	</div>
	<!-- /wp:column -->
	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:heading {"level":4} -->
		<h4 class="wp-block-heading"><?php esc_html_e( 'Reach me', 'jardin-theme' ); ?></h4>
		<!-- /wp:heading -->
		<!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","justifyContent":"left","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"0"}}} -->
			<!-- wp:navigation-link {"label":"/contact","type":"custom","url":"/contact/","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"/social","type":"custom","url":"/social/","kind":"custom"} /-->
		<!-- /wp:navigation -->
		<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"var:preset|spacing|2"}}}} -->
		<p style="margin-top:var(--wp--preset--spacing--2)"><a href="https://bsky.app/profile/jasonrouet.com" rel="me noopener" target="_blank"><?php esc_html_e( 'Bluesky', 'jardin-theme' ); ?></a><br /><a href="https://www.linkedin.com/in/jasonrouet" rel="me noopener" target="_blank"><?php esc_html_e( 'LinkedIn', 'jardin-theme' ); ?></a><br /><a href="https://pouet.chapril.org/@jrouet" rel="me noopener" target="_blank"><?php esc_html_e( 'Mastodon', 'jardin-theme' ); ?></a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:html -->
<div class="webring">
	<span class="webring-label">
		<a href="https://xn--sr8hvo.ws/" target="_blank" rel="noopener"><?php esc_html_e( 'IndieWeb webring', 'jardin-theme' ); ?> <span aria-hidden="true">🕸💍</span></a>
	</span>
	<span class="webring-nav">
		<a class="webring-prev" href="https://xn--sr8hvo.ws/previous"><?php esc_html_e( '← previous site', 'jardin-theme' ); ?></a>
		<span class="webring-sep" aria-hidden="true">·</span>
		<a class="webring-next" href="https://xn--sr8hvo.ws/next"><?php esc_html_e( 'next site →', 'jardin-theme' ); ?></a>
	</span>
</div>
<!-- /wp:html -->
