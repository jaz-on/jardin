<?php
/**
 * Title: Event — meta via block bindings (sample)
 * Slug: jardin-theme/event-bindings-sample
 * Categories: featured
 * Description: Sample group binding event date, location, and roles to core paragraphs (requires jardin-events).
 * Inserter: no
 *
 * @package Jardin_Theme
 */
?>
<!-- wp:group {"metadata":{"name":"Event meta (bindings sample)"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"jardin-events/event-date-formatted"}}}} -->
	<p></p>
	<!-- /wp:paragraph -->
	<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"jardin-events/event-location"}}}} -->
	<p></p>
	<!-- /wp:paragraph -->
	<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"jardin-events/event-roles-plain"}}}} -->
	<p></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
