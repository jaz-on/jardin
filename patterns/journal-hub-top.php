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
<p class="has-text-muted-color has-text-color has-sm-font-size"><?php echo esc_html__( 'A mixed timeline of long posts, notes, likes, bookmarks, events, beer reviews, music jams, and TIL—newest first. Raw scrobbles and tastings without notes stay in their own views; use the filters to narrow by kind.', 'jardin' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<?php echo jardin_get_journal_filters_markup(); ?>
<!-- /wp:html -->
