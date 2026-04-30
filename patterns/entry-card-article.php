<?php
/**
 * Title: Entry card article
 * Slug: jardin-theme/entry-card-article
 * Categories: text
 * Description: Article archive card matching mockup structure.
 * Inserter: no
 * Block Types: core/post-template
 *
 * @package Jardin_Theme
 */
?>
<!-- wp:group {"className":"entry","layout":{"type":"constrained"},"style":{"spacing":{"blockGap":"var:preset|spacing|2"}}} -->
<div class="wp-block-group entry">
	<!-- wp:group {"className":"entry-title-row","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"flex-start","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|2"}}} -->
	<div class="wp-block-group entry-title-row">
		<!-- wp:paragraph {"className":"entry-kind"} -->
		<p class="entry-kind">post</p>
		<!-- /wp:paragraph -->
		<!-- wp:post-title {"isLink":true,"level":3,"className":"entry-title"} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"entry-meta-row","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"flex-start","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|2"}}} -->
	<div class="wp-block-group entry-meta-row">
		<!-- wp:post-date /-->
		<!-- wp:post-terms {"term":"category","separator":" ","className":"entry-cat"} /-->
		<!-- wp:post-terms {"term":"post_tag","separator":" ","className":"entry-tag"} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:post-excerpt {"moreText":"","className":"entry-excerpt"} /-->
</div>
<!-- /wp:group -->
