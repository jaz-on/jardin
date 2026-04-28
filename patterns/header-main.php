<?php
/**
 * Title: Header — marque, toolbar, navigation
 * Slug: jardin/header-main
 * Categories: header
 * Description: Contenu du header (ligne brand + toolbar, puis nav) — même principe que jardin/footer-main. Voir mockup.html header.site ~1005–1133.
 * Inserter: no
 *
 * @package Jardin
 */

?>
<!-- wp:group {"className":"site-row site-row-brand","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"},"style":{"spacing":{"blockGap":"var:preset|spacing|4","padding":{"bottom":"var:preset|spacing|3"}}}} -->
<div class="wp-block-group site-row site-row-brand">
	<!-- wp:pattern {"slug":"jardin/site-brand"} /-->

	<!-- wp:pattern {"slug":"jardin/site-toolbar"} /-->
</div>
<!-- /wp:group -->

<!-- wp:navigation {"className":"primary jardin-primary-nav","overlayMenu":"never","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"left","orientation":"horizontal","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"0"}}} /-->
