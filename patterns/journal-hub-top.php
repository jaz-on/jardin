<?php
/**
 * Title: Journal hub — intro and filters
 * Slug: jardin/journal-hub-top
 * Categories: text
 * Description: Intro paragraph and ?kind= filter links for the journal page template.
 * Inserter: no
 *
 * @package Jardin
 */

?>
<!-- wp:paragraph {"fontSize":"sm","textColor":"text-muted"} -->
<p class="has-text-muted-color has-text-color has-sm-font-size"><?php echo esc_html__( 'A mixed timeline of long posts, notes, likes, and TIL articles—newest first. Use the filters to narrow by kind. (More post types join this hub after the custom plugins ship.)', 'jardin' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<?php echo jardin_get_journal_filters_markup(); ?>
<!-- /wp:html -->
