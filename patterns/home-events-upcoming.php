<?php
/**
 * Title: Home — Events upcoming
 * Slug: jardin/home-events-upcoming
 * Categories: query
 * Description: Upcoming events widget for the home page. Uses jardin/events-upcoming query namespace (event_date >= today, ASC). Displays nothing if no upcoming events are found.
 * Inserter: no
 *
 * @package Jardin_Theme */

?>
<!-- wp:group {"className":"events-upcoming","style":{"spacing":{"blockGap":"var:preset|spacing|3"}}} -->
<div class="wp-block-group events-upcoming">

	<!-- wp:heading {"level":3} -->
	<h3>
		<span class="events-upcoming-title"><?php esc_html_e( 'IRL', 'jardin-theme' ); ?></span>
		<a href="<?php echo esc_url( home_url( '/evenements/' ) ); ?>" class="events-upcoming-link"><?php esc_html_e( '/evenements →', 'jardin-theme' ); ?></a>
	</h3>
	<!-- /wp:heading -->

	<?php if ( function_exists( 'jardin_events_get_post_type' ) && post_type_exists( jardin_events_get_post_type() ) ) : ?>
	<!-- wp:query {"queryId":20,"namespace":"jardin/events-upcoming","query":{"perPage":5,"pages":0,"offset":0,"postType":"event","order":"asc","orderBy":"meta_value","metaKey":"event_date","sticky":"","inherit":false}} -->
	<div class="wp-block-query">
		<!-- wp:post-template {"layout":{"type":"default"}} -->
			<!-- wp:group {"className":"event-row","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"},"style":{"spacing":{"blockGap":"var:preset|spacing|3"}}} -->
			<div class="wp-block-group event-row">
				<!-- wp:group {"className":"what","layout":{"type":"constrained"},"style":{"spacing":{"blockGap":"var:preset|spacing|1"}}} -->
				<div class="wp-block-group what">
					<!-- wp:post-title {"isLink":true,"level":0,"className":"entry-title"} /-->
					<!-- wp:jardin-events/event-inline-meta /-->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
		<!-- wp:query-no-results -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
	<?php endif; ?>

	<!-- wp:paragraph {"className":"events-upcoming-footer","fontSize":"sm","textColor":"text-muted"} -->
	<p class="events-upcoming-footer has-text-muted-color has-text-color has-sm-font-size"><?php esc_html_e( "J'habite la belle ville de Cognac, fais-moi signe pour prendre un café si tu y passes ! ✌️", 'jardin-theme' ); ?></p>
	<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
