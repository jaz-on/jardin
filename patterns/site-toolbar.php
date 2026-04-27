<?php
/**
 * Title: Site toolbar
 * Slug: jardin/site-toolbar
 * Categories: hidden
 * Description: Header utilities — language switch, search, theme picker, LFM demo, coffee. Used in parts/header.html row 1 right side.
 * Inserter: no
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

// ── Language switch ──────────────────────────────────────────────────────────
$lang_switch = '';
if ( function_exists( 'pll_the_languages' ) ) {
	$langs = pll_the_languages( array( 'raw' => 1 ) );
	if ( ! empty( $langs ) ) {
		$buttons = '';
		foreach ( $langs as $lang ) {
			$current = ! empty( $lang['current_lang'] );
			$buttons .= sprintf(
				'<a href="%s" lang="%s" hreflang="%s"%s>%s</a>',
				esc_url( $lang['url'] ),
				esc_attr( $lang['slug'] ),
				esc_attr( $lang['slug'] ),
				$current ? ' aria-current="true"' : '',
				esc_html( strtoupper( $lang['slug'] ) )
			);
		}
		$lang_switch = '<div class="lang-switch" role="group" aria-label="' . esc_attr__( 'Langue', 'jardin' ) . '">' . $buttons . '</div>';
	}
}

// ── Theme-toggle (palette picker) ─────────────────────────────────────────────
$theme_toggle_html = '';
if ( function_exists( 'do_blocks' ) ) {
	$theme_toggle_html = do_blocks( '<!-- wp:jardin/theme-toggle /-->' );
}

// ── URLs ─────────────────────────────────────────────────────────────────────
$search_url = home_url( '/recherche/' );
$coffee_url = home_url( '/coffee/' );

// ── SVGs ─────────────────────────────────────────────────────────────────────
$svg_search = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>';
$svg_music  = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>';
$svg_coffee = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 2v2"/><path d="M14 2v2"/><path d="M16 8a1 1 0 0 1 1 1v8a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V9a1 1 0 0 1 1-1h14a4 4 0 1 1 0 8h-1"/><path d="M6 2v2"/></svg>';

?>
<!-- wp:html -->
<div class="site-toolbar" role="toolbar" aria-label="<?php echo esc_attr__( 'Utilitaires du site', 'jardin' ); ?>">

	<?php echo $lang_switch; // phpcs:ignore WordPress.Security.EscapeOutput ?>

	<a class="icon-btn"
	   href="<?php echo esc_url( $search_url ); ?>"
	   aria-label="<?php echo esc_attr__( 'Rechercher', 'jardin' ); ?>"
	   rel="search"
	><?php echo $svg_search; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>

	<?php echo $theme_toggle_html; // phpcs:ignore WordPress.Security.EscapeOutput ?>

	<button class="icon-btn"
	        id="lfm-demo-toggle"
	        aria-label="<?php echo esc_attr__( 'Simuler lecture Last.fm', 'jardin' ); ?>"
	        title="Demo LFM"
	        aria-pressed="false"
	><?php echo $svg_music; // phpcs:ignore WordPress.Security.EscapeOutput ?></button>

	<a class="icon-btn coffee-btn"
	   href="<?php echo esc_url( $coffee_url ); ?>"
	   aria-label="<?php echo esc_attr__( 'Me soutenir', 'jardin' ); ?>"
	   title="<?php echo esc_attr__( 'Me soutenir', 'jardin' ); ?>"
	><?php echo $svg_coffee; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>

</div>
<!-- /wp:html -->
