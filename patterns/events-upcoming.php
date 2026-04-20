<?php
/**
 * Title: Prochains événements
 * Slug: jardin/upcoming-events
 * Categories: featured, query
 * Description: Liste des prochains événements (plugin Jardin Events — classe jardin-events-query--upcoming).
 * Viewport Width: 800
 *
 * @package Jardin
 */
?>
<!-- wp:group {"className":"jardin-events-upcoming","layout":{"type":"constrained"}} -->
<div class="wp-block-group jardin-events-upcoming">
	<!-- wp:heading {"level":2} -->
	<h2>Prochains événements</h2>
	<!-- /wp:heading -->

	<!-- wp:separator {"className":"is-style-wide"} -->
	<hr class="wp-block-separator is-style-wide" />
	<!-- /wp:separator -->

	<!-- wp:query {"className":"jardin-events-query--upcoming","query":{"perPage":3,"pages":0,"offset":0,"postType":"event","order":"asc","orderBy":"date","inherit":false},"displayLayout":{"type":"list"}} -->
	<div class="wp-block-query">
		<!-- wp:post-template -->
		<!-- wp:group {"className":"jardin-events-item","layout":{"type":"constrained"}} -->
		<div class="wp-block-group jardin-events-item">
			<!-- wp:post-title {"level":3,"className":"jardin-events-item-title"} /-->
			<!-- wp:post-excerpt {"showMoreOnNewLine":false} /-->
			<!-- wp:post-meta {"key":"event_date","className":"jardin-events-item-meta"} /-->
			<!-- wp:post-meta {"key":"event_location","className":"jardin-events-item-meta"} /-->
			<!-- wp:post-meta {"key":"event_link","className":"jardin-events-item-meta"} /-->
		</div>
		<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
		<!-- wp:paragraph -->
		<p></p>
		<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
