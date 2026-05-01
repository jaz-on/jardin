<?php
/**
 * Title: Home — Events upcoming
 * Slug: jardin-theme/home-events-upcoming
 * Categories: query
 * Description: Upcoming events widget for the home page. Uses jardin-theme/events-upcoming query namespace (event_date >= today, ASC). Displays nothing if no upcoming events are found.
 * Inserter: no
 *
 * @package Jardin_Theme */

$jardin_events_archive_url   = function_exists( 'jardin_get_event_archive_url' ) ? jardin_get_event_archive_url() : '';
$jardin_events_archive_label = function_exists( 'jardin_get_event_archive_label' ) ? jardin_get_event_archive_label() : '';
if ( '' === $jardin_events_archive_url ) {
	$jardin_events_archive_url = trailingslashit( home_url( '/evenements/' ) );
}
if ( '' === $jardin_events_archive_label ) {
	$jardin_events_archive_label = '/evenements';
}

?>
<!-- wp:group {"className":"events-upcoming","style":{"spacing":{"blockGap":"var:preset|spacing|3"}}} -->
<div class="wp-block-group events-upcoming">

	<!-- wp:heading {"level":3} -->
	<h3>
		<span class="events-upcoming-title"><?php esc_html_e( 'IRL', 'jardin-theme' ); ?></span>
		<a href="<?php echo esc_url( $jardin_events_archive_url ); ?>" class="events-upcoming-link"><?php echo esc_html( $jardin_events_archive_label . ' →' ); ?></a>
	</h3>
	<!-- /wp:heading -->

	<?php if ( function_exists( 'jardin_events_get_post_type' ) && post_type_exists( jardin_events_get_post_type() ) ) : ?>
	<!-- wp:query {"queryId":20,"namespace":"jardin-theme/events-upcoming","query":{"perPage":5,"pages":0,"offset":0,"postType":"event","order":"asc","orderBy":"meta_value","metaKey":"event_date","sticky":"","inherit":false}} -->
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
	<p class="events-upcoming-footer has-text-muted-color has-text-color has-sm-font-size"><?php esc_html_e( 'I live in Cognac — happy to grab coffee if you are passing through. ✌️', 'jardin-theme' ); ?></p>
	<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
