<?php
/**
 * Title: Placeholder — Now / listening
 * Slug: jardin/placeholder-now
 * Categories: text
 * Description: Temporary notice until jardin-scrobbler blocks are wired. Insert at the top of the Now page if needed.
 *
 * @package Jardin
 */

?>
<!-- wp:paragraph {"textColor":"text-muted"} -->
<p class="has-text-muted-color has-text-color"><?php echo esc_html( jardin_get_placeholder_message( 'now' ) ); ?></p>
<!-- /wp:paragraph -->
