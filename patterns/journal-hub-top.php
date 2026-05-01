<?php
/**
 * Title: Journal hub — intro and filters
 * Slug: jardin-theme/journal-hub-top
 * Categories: text
 * Description: Intro, h2, primary ?kind= filters for the Journal hub page (template page-journal).
 * Inserter: no
 *
 * @package Jardin_Theme */

?>
<!-- wp:group {"align":"wide","className":"journal-hub-block","layout":{"type":"constrained"},"style":{"spacing":{"blockGap":"var:preset|spacing|4"}}} -->
<div class="wp-block-group alignwide journal-hub-block">
	<!-- wp:paragraph {"fontSize":"sm","textColor":"text-muted"} -->
	<p class="has-text-muted-color has-text-color has-sm-font-size"><?php echo esc_html__( 'A mixed timeline of long articles, short-form activities (bookmarks, likes, listens, beer check-ins, and more), events, and TIL—newest first. Raw scrobbles and tastings without a write-up stay in their own views; use the filters to narrow by type.', 'jardin-theme' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:group {"className":"feed-header","layout":{"type":"default"},"style":{"spacing":{"blockGap":"var:preset|spacing|3"}}} -->
	<div class="wp-block-group feed-header">
		<!-- wp:heading {"level":2} -->
		<h2 class="wp-block-heading"><?php esc_html_e( 'Filter journal', 'jardin-theme' ); ?></h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:html -->
	<?php echo jardin_get_journal_filters_markup(); ?>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->
