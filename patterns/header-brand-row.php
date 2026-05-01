<?php
/**
 * Title: Header — brand row (logo + toolbar)
 * Slug: jardin-theme/header-brand-row
 * Categories: header
 * Description: First header row: JR mark and header utilities. Edit the nested Site brand or Header utilities blocks; use together with Header — nav row.
 * Inserter: no
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

?>
<!-- wp:group {"className":"site-row site-row-brand","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|4"}}} -->
<div class="wp-block-group site-row site-row-brand">
	<!-- wp:pattern {"slug":"jardin-theme/site-brand"} /-->

	<!-- wp:pattern {"slug":"jardin-theme/site-toolbar"} /-->
</div>
<!-- /wp:group -->
