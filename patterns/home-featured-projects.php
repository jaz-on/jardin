<?php
/**
 * Title: Home — Featured projects
 * Slug: jardin-theme/home-featured-projects
 * Categories: text
 * Description: Pinned projects grid (3 columns). Placeholder until CPT "project" is registered and meta _featured_on_home is implemented — tracked in mockup-dev-parity-cycle.md § 7 (Dettes C).
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

<!-- wp:columns {"align":"wide","className":"projects-grid","style":{"spacing":{"blockGap":"var:preset|spacing|4"}}} -->
<div class="wp-block-columns alignwide projects-grid">

	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:group {"className":"is-style-card project-card"} -->
		<div class="wp-block-group is-style-card project-card">
			<!-- wp:heading {"level":3} --><h3>feed&#8209;favorites <span class="status"><?php esc_html_e( 'actif', 'jardin-theme' ); ?></span></h3><!-- /wp:heading -->
			<!-- wp:paragraph {"className":"desc"} --><p class="desc"><?php esc_html_e( 'Workflow de veille commentée depuis un flux RSS de favoris.', 'jardin-theme' ); ?></p><!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:column -->

	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:group {"className":"is-style-card project-card"} -->
		<div class="wp-block-group is-style-card project-card">
			<!-- wp:heading {"level":3} --><h3>jardin-theme <span class="status"><?php esc_html_e( 'en cours', 'jardin-theme' ); ?></span></h3><!-- /wp:heading -->
			<!-- wp:paragraph {"className":"desc"} --><p class="desc"><?php esc_html_e( 'Thème FSE from‑scratch minimaliste pour ce site.', 'jardin-theme' ); ?></p><!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:column -->

	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:group {"className":"is-style-card project-card"} -->
		<div class="wp-block-group is-style-card project-card">
			<!-- wp:heading {"level":3} --><h3>french&#8209;typo</h3><!-- /wp:heading -->
			<!-- wp:paragraph {"className":"desc"} --><p class="desc"><?php esc_html_e( 'Typo française côté front, Polylang‑aware. Espaces insécables et tout le tralala.', 'jardin-theme' ); ?></p><!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:column -->

</div>
<!-- /wp:columns -->
