<?php
/**
 * Title: Home — Featured projects
 * Slug: jardin-theme/home-featured-projects
 * Categories: text
 * Description: Pinned projects grid sourced from the project CPT.
 * Inserter: no
 *
 * @package Jardin_Theme */

?>
<!-- wp:heading {"level":2,"align":"wide","className":"section-with-link"} -->
<h2 class="section-with-link"><?php
	echo wp_kses(
		sprintf(
			/* translators: %s: link to /projets/ */
			__( 'Projets épinglés <a href="%s" class="section-link">/projets →</a>', 'jardin-theme' ),
			esc_url( home_url( '/projets/' ) )
		),
		array( 'a' => array( 'href' => true, 'class' => true ) )
	);
?></h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":41,"query":{"perPage":3,"pages":0,"offset":0,"postType":"project","order":"desc","orderBy":"date","inherit":false},"className":"jardin-projects-query--featured"} -->
<div class="wp-block-query jardin-projects-query--featured">
	<!-- wp:post-template {"layout":{"type":"grid","columnCount":3},"className":"projects-grid"} -->
		<!-- wp:group {"className":"project-card is-style-card","layout":{"type":"constrained"},"style":{"spacing":{"blockGap":"var:preset|spacing|2"}}} -->
		<div class="wp-block-group project-card is-style-card">
			<!-- wp:group {"className":"pc-head","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"baseline"}} -->
			<div class="wp-block-group pc-head">
				<!-- wp:post-title {"level":3,"isLink":true} /-->
				<!-- wp:post-terms {"term":"project_status","className":"status"} /-->
			</div>
			<!-- /wp:group -->
			<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":20,"className":"desc"} /-->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->
	<!-- wp:query-no-results -->
		<!-- wp:paragraph {"className":"u-text-meta-sm"} -->
		<p class="u-text-meta-sm"><?php esc_html_e( 'Aucun projet épinglé pour le moment.', 'jardin-theme' ); ?></p>
		<!-- /wp:paragraph -->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
