<?php
/**
 * Title: Home — Feed
 * Slug: jardin/home-feed
 * Categories: query
 * Description: Mixed journal feed for the home page: h2 heading, filter links navigating to /journal/?kind=, then a 10-item journal-mixed query with entry-card sub-pattern. Client-side JS filtering is deferred to phase C (mockup-dev-parity-cycle.md § 7).
 * Inserter: no
 *
 * @package Jardin
 */

$journal_url = home_url( '/journal/' );
?>
<!-- wp:group {"className":"feed-header","style":{"spacing":{"blockGap":"var:preset|spacing|3","margin":{"bottom":"var:preset|spacing|4"}}}} -->
<div class="wp-block-group feed-header">

	<!-- wp:heading {"level":2,"className":"section-with-link"} -->
	<h2 class="section-with-link"><?php
		echo wp_kses(
			sprintf(
				/* translators: %s: link to /journal/ */
				__( 'Mon flux <a href="%s" class="section-link">tout voir →</a>', 'jardin' ),
				esc_url( $journal_url )
			),
			array( 'a' => array( 'href' => true, 'class' => true ) )
		);
	?></h2>
	<!-- /wp:heading -->

	<!-- wp:html -->
	<div class="feed-filters" role="navigation" aria-label="<?php echo esc_attr__( 'Filtrer par type', 'jardin' ); ?>">
		<a class="ff-btn active" href="<?php echo esc_url( $journal_url ); ?>"><?php esc_html_e( 'tous', 'jardin' ); ?></a>
		<a class="ff-btn" href="<?php echo esc_url( add_query_arg( 'kind', 'post', $journal_url ) ); ?>">
			<svg aria-hidden="true" class="ff-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><use href="#i-post"/></svg><?php esc_html_e( 'articles', 'jardin' ); ?>
		</a>
		<a class="ff-btn" href="<?php echo esc_url( add_query_arg( 'kind', 'note', $journal_url ) ); ?>">
			<svg aria-hidden="true" class="ff-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><use href="#i-note"/></svg><?php esc_html_e( 'notes', 'jardin' ); ?>
		</a>
		<a class="ff-btn" href="<?php echo esc_url( add_query_arg( 'kind', 'event', $journal_url ) ); ?>">
			<svg aria-hidden="true" class="ff-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><use href="#i-event"/></svg><?php esc_html_e( 'événements', 'jardin' ); ?>
		</a>
	</div>
	<!-- /wp:html -->

</div>
<!-- /wp:group -->

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

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"var:preset|spacing|4"}}}} -->
<p class="has-text-align-center" style="margin-top:var(--wp--preset--spacing--4)"><a href="<?php echo esc_url( $journal_url ); ?>"><?php esc_html_e( 'Tout le journal →', 'jardin' ); ?></a></p>
<!-- /wp:paragraph -->
