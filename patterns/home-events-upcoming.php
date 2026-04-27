<?php
/**
 * Title: Home — Events upcoming
 * Slug: jardin/home-events-upcoming
 * Categories: query
 * Description: Upcoming events widget for the home page. Uses jardin/events-upcoming query namespace (event_date >= today, ASC). Displays nothing if no upcoming events are found.
 * Inserter: no
 *
 * @package Jardin
 */

?>
<!-- wp:group {"className":"events-upcoming","style":{"spacing":{"blockGap":"var:preset|spacing|3"}}} -->
<div class="wp-block-group events-upcoming">

	<!-- wp:heading {"level":3} -->
	<h3><?php
		echo wp_kses(
			sprintf(
			/* translators: %s: link to /evenements/ */
			__( 'Où prendre un café IRL&#160;? <a href="%s" class="events-upcoming-link">/evenements →</a>', 'jardin' ),
				esc_url( home_url( '/evenements/' ) )
			),
			array( 'a' => array( 'href' => true, 'class' => true ) )
		);
	?></h3>
	<!-- /wp:heading -->

	<!-- wp:query {"queryId":20,"namespace":"jardin/events-upcoming","query":{"perPage":5,"pages":0,"offset":0,"postType":"event","order":"asc","orderBy":"meta_value","metaKey":"event_date","sticky":"","inherit":false}} -->
	<div class="wp-block-query">
		<!-- wp:post-template {"layout":{"type":"default"}} -->
			<!-- wp:group {"className":"event-row","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"},"style":{"spacing":{"blockGap":"var:preset|spacing|3"}}} -->
			<div class="wp-block-group event-row">
				<!-- wp:post-date {"format":"j F Y","className":"entry-when"} /-->
				<!-- wp:post-title {"isLink":true,"level":0,"className":"entry-title"} /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
		<!-- wp:query-no-results -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->

	<!-- wp:paragraph {"className":"events-upcoming-footer","fontSize":"sm","textColor":"text-muted"} -->
	<p class="events-upcoming-footer has-text-muted-color has-text-color has-sm-font-size"><?php esc_html_e( "Sinon, j'habite Cognac, fais-moi signe si tu passes dans la r\u{00e9}gion\u{00a0}! ✌️", 'jardin' ); ?></p>
	<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
