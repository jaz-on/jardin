<?php
/**
 * Title: Site brand
 * Slug: jardin/site-brand
 * Categories: text
 * Description: JR boxed-serif mark SVG, linked to the home page. Replaces wp:site-title in the header.
 * Inserter: no
 *
 * @package Jardin
 */

?>
<!-- wp:html -->
<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-mark" aria-label="<?php echo esc_attr__( 'Retour à l\'accueil', 'jardin' ); ?>" rel="home">
	<svg width="44" height="44" viewBox="0 0 44 44" fill="none" aria-hidden="true" focusable="false">
		<rect x="2" y="2" width="40" height="40" rx="8" stroke="currentColor" stroke-width="2.5" fill="none"/>
		<text x="22" y="29" text-anchor="middle" font-family="Georgia, 'Source Serif Pro', serif" font-size="18" font-weight="600" fill="currentColor" letter-spacing="-0.5">JR</text>
	</svg>
</a>
<!-- /wp:html -->
