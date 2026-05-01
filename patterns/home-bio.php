<?php
/**
 * Title: Home — Bio
 * Slug: jardin-theme/home-bio
 * Categories: text
 * Description: Introductory bio paragraph for the home page.
 * Inserter: no
 *
 * @package Jardin_Theme */

?>
<!-- wp:group {"align":"wide","className":"home-bio-shell","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide home-bio-shell">
	<!-- wp:paragraph {"className":"bio"} -->
	<p class="bio"><?php
		echo wp_kses_post(
			sprintf(
				/* translators: 1: Mediapapa link 2: WordPress Francophone link */
				__( 'Jason Rouet, WordPress product manager at %1$s, president of %2$s, open-web contributor for years. Here I tinker, jot notes, and tell stories.', 'jardin-theme' ),
				'<a href="https://www.wp-mediapapa.com/" class="inline-brand" rel="noopener">Mediapapa</a>',
				'<a href="https://wpfr.net" class="inline-brand" target="_blank" rel="noopener">WordPress Francophone</a>'
			)
		);
	?></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
