<?php
/**
 * Title: Entry card
 * Slug: jardin-theme/entry-card
 * Categories: text
 * Description: Entry row for Query Loop (.entry + server-side data-kind / data-note-kind).
 * Inserter: no
 * Block Types: core/post-template
 *
 * @package Jardin_Theme */

?>
<!-- wp:group {"className":"entry","layout":{"type":"constrained"},"style":{"spacing":{"blockGap":"var:preset|spacing|2"}}} -->
<div class="wp-block-group entry">
	<!-- wp:group {"className":"entry__meta-row","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"flex-start","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|2"}}} -->
	<div class="wp-block-group entry__meta-row">
		<!-- wp:post-date /-->
		<!-- wp:post-terms {"term":"post_tag","separator":" "} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:post-title {"isLink":true,"level":3,"className":"entry-title"} /-->

	<!-- wp:post-excerpt {"moreText":"","className":"entry-excerpt"} /-->
</div>
<!-- /wp:group -->
