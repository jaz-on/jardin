<?php
/**
 * Title: Post meta row
 * Slug: jardin-theme/post-meta-bar
 * Categories: text
 * Description: Published date, modified date, author, categories, and tags in one row. Insert in single templates or per-post in the editor.
 *
 * @package Jardin_Theme */

?>
<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap"},"style":{"spacing":{"blockGap":"var:preset|spacing|3"}}} -->
<div class="wp-block-group">
	<!-- wp:post-date {"fontSize":"xs"} /-->
	<!-- wp:post-date {"displayType":"modified","fontSize":"xs"} /-->
	<!-- wp:post-author-name {"isLink":true,"fontSize":"xs"} /-->
	<!-- wp:post-terms {"term":"category","fontSize":"xs"} /-->
	<!-- wp:post-terms {"term":"post_tag","fontSize":"xs"} /-->
</div>
<!-- /wp:group -->
