<?php
/**
 * Title: Home — Bio
 * Slug: jardin/home-bio
 * Categories: text
 * Description: Introductory bio paragraph for the home page.
 * Inserter: no
 *
 * @package Jardin
 */

$icon_mediapapa = '<span class="inline-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="20" height="20" fill="none"><rect width="24" height="24" rx="5" fill="#2d6bd8"/><path d="M7 9h10v7a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V9z" fill="#fff"/><circle cx="12" cy="13" r="2" fill="#2d6bd8"/><rect x="9" y="6" width="6" height="2" rx="0.5" fill="#fff"/></svg></span>';
$icon_wpfr      = '<span class="inline-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="20" height="20" fill="none"><circle cx="12" cy="12" r="11" fill="#21759b"/><text x="12" y="16.5" font-family="Georgia, serif" font-size="13" font-weight="700" text-anchor="middle" fill="#fff">W</text></svg></span>';
?>
<!-- wp:html -->
<p class="bio">
	<?php
	printf(
		/* translators: 1: Mediapapa icon 2: Mediapapa link 3: WP icon 4: WP Francophone link */
		esc_html__( 'Jason Rouet, product manager WordPress chez %1$s%2$s, président de l\'asso %3$s%4$s, contributeur open‑web depuis un bon bout de temps. Ici je bidouille, je note, je raconte.', 'jardin' ),
		$icon_mediapapa, // phpcs:ignore WordPress.Security.EscapeOutput
		'<a href="https://www.wp-mediapapa.com/" rel="noopener">Mediapapa</a>',
		$icon_wpfr, // phpcs:ignore WordPress.Security.EscapeOutput
		'<a href="https://wpfr.net" target="_blank" rel="noopener">WordPress Francophone</a>'
	);
	?>
</p>
<!-- /wp:html -->
