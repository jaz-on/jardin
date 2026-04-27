<?php
/**
 * Title: Entry card
 * Slug: jardin/entry-card
 * Categories: text
 * Description: Reusable entry card for use inside a Query Loop post-template: title (h3 linked), date, tags, excerpt.
 * Inserter: no
 * Block Types: core/post-template
 *
 * @package Jardin
 */

?>
<!-- wp:post-title {"isLink":true,"level":3} /-->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap"},"style":{"spacing":{"blockGap":"var:preset|spacing|2","margin":{"bottom":"var:preset|spacing|2"}}}} -->
<div class="wp-block-group">
	<!-- wp:post-date /-->
	<!-- wp:post-terms {"term":"post_tag","separator":" "} /-->
</div>
<!-- /wp:group -->

<!-- wp:post-excerpt {"moreText":""} /-->
