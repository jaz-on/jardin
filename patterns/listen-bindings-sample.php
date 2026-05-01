<?php
/**
 * Title: Listen — track/artist via block bindings (sample)
 * Slug: jardin-theme/listen-bindings-sample
 * Categories: featured
 * Description: Sample paragraphs bound to listen post meta (requires jardin-scrobbles).
 * Inserter: no
 *
 * @package Jardin_Theme
 */
?>
<!-- wp:group {"metadata":{"name":"Listen meta (bindings sample)"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"jardin-scrobbles/listen-track-name"}}}} -->
	<p></p>
	<!-- /wp:paragraph -->
	<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"jardin-scrobbles/listen-artist-name"}}}} -->
	<p></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
