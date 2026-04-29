<?php
/**
 * Title: Home — Feed
 * Slug: jardin/home-feed
 * Categories: query
 * Description: Mixed journal feed: heading, client-side filter pills + ?kind= URL, journal-mixed query, entry-card.
 * Inserter: no
 *
 * @package Jardin_Theme */

$journal_url = home_url( '/journal/' );
?>
<!-- wp:group {"className":"home-feed-section","style":{"spacing":{"blockGap":"var:preset|spacing|4","margin":{"bottom":"var:preset|spacing|4"}}}} -->
<div class="wp-block-group home-feed-section">
	<!-- wp:group {"className":"feed-header","style":{"spacing":{"blockGap":"var:preset|spacing|3"}}} -->
	<div class="wp-block-group feed-header">
		<!-- wp:heading {"level":2,"className":"section-with-link"} -->
		<h2 class="section-with-link"><?php
			echo wp_kses(
				sprintf(
					/* translators: %s: link to /journal/ */
					__( 'Mon flux <a href="%s" class="section-link">tout voir →</a>', 'jardin-theme' ),
					esc_url( $journal_url )
				),
				array( 'a' => array( 'href' => true, 'class' => true ) )
			);
		?></h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:html -->
	<div class="feed-filters home-feed-filters" role="navigation" aria-label="<?php echo esc_attr__( 'Filtrer par type', 'jardin-theme' ); ?>" data-filter="all" data-jardin-feed-init="1">
		<button type="button" class="ff-btn active" data-type="all"><?php esc_html_e( 'tous', 'jardin-theme' ); ?></button>
		<button type="button" class="ff-btn" data-type="post">
			<svg aria-hidden="true" class="ff-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><use href="#i-post"/></svg><?php esc_html_e( 'articles', 'jardin-theme' ); ?>
		</button>
		<button type="button" class="ff-btn" data-type="note">
			<svg aria-hidden="true" class="ff-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><use href="#i-note"/></svg><?php echo esc_html( function_exists( 'jardin_get_activity_nav_label' ) ? jardin_get_activity_nav_label() : __( 'activité', 'jardin-theme' ) ); ?>
		</button>
		<button type="button" class="ff-btn" data-type="event">
			<svg aria-hidden="true" class="ff-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><use href="#i-event"/></svg><?php esc_html_e( 'événements', 'jardin-theme' ); ?>
		</button>
	</div>
	<!-- /wp:html -->

	<!-- wp:query {"queryId":2,"namespace":"jardin/journal-mixed","query":{"perPage":10,"pages":0,"offset":0,"postType":["post","iwcpt_note","iwcpt_like","favorite","event","beer_checkin","listen"],"order":"desc","orderBy":"date","search":"","exclude":[],"sticky":"exclude","inherit":false}} -->
	<div class="wp-block-query">
		<!-- wp:post-template {"layout":{"type":"default","columnCount":1},"className":"entries"} -->
			<!-- wp:pattern {"slug":"jardin/entry-card"} /-->
			<!-- wp:separator {"className":"is-style-dashed-faint"} /-->
		<!-- /wp:post-template -->
		<!-- wp:query-no-results -->
			<!-- wp:pattern {"slug":"jardin/query-empty-home"} /-->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"var:preset|spacing|4"}}}} -->
<p class="has-text-align-center" style="margin-top:var(--wp--preset--spacing--4)"><a href="<?php echo esc_url( $journal_url ); ?>"><?php esc_html_e( 'Tout le journal →', 'jardin-theme' ); ?></a></p>
<!-- /wp:paragraph -->
