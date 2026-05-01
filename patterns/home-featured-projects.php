<?php
/**
 * Title: Home — Featured projects
 * Slug: jardin-theme/home-featured-projects
 * Categories: text
 * Description: Pinned projects grid; section link starter /projets/ — edit in Site Editor.
 * Inserter: no
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

$projects_section_url   = trailingslashit( home_url( '/projets/' ) );
$projects_section_label = '/projets';

?>
<!-- wp:group {"align":"wide","className":"home-featured-projects-wrap","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide home-featured-projects-wrap">
<!-- wp:heading {"level":2,"className":"section-with-link"} -->
<h2 class="wp-block-heading section-with-link"><?php
	echo wp_kses(
				sprintf(
					/* translators: 1: projects section URL (starter /projets/), 2: path label shown in the link */
					__( 'Pinned projects <a href="%1$s" class="section-link">%2$s →</a>', 'jardin-theme' ),
					esc_url( $projects_section_url ),
					esc_html( $projects_section_label )
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
		<p class="u-text-meta-sm"><?php esc_html_e( 'No pinned projects yet.', 'jardin-theme' ); ?></p>
		<!-- /wp:paragraph -->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
</div>
<!-- /wp:group -->
