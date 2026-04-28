<?php
/**
 * Title: Site toolbar
 * Slug: jardin/site-toolbar
 * Categories: hidden
 * Description: Header utilities — bloc langue dédié (.toolbar-lang), puis icônes segmentées (.toolbar-chrome).
 * Inserter: no
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

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

$theme_toggle_html = '';
if ( function_exists( 'do_blocks' ) ) {
	$theme_toggle_html = do_blocks( '<!-- wp:jardin/theme-toggle /-->' );
}
if ( '' === trim( wp_strip_all_tags( (string) $theme_toggle_html ) ) && function_exists( 'jardin_get_theme_toggle_markup' ) ) {
	$theme_toggle_html = jardin_get_theme_toggle_markup();
}

$search_url   = home_url( '/recherche/' );
$soutenir_url = home_url( '/soutenir/' );

// SVG paths copied from mockup.html (toolbar desktop).
$svg_search = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>';
$svg_music  = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>';
$svg_burger = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>';
$svg_coffee = '<svg class="coffee-icon coffee-icon-coffee" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 2v2"/><path d="M14 2v2"/><path d="M16 8a1 1 0 0 1 1 1v8a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V9a1 1 0 0 1 1-1h14a4 4 0 1 1 0 8h-1"/><path d="M6 2v2"/></svg>';
$svg_cherry = '<svg class="coffee-icon coffee-icon-cherry" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 17a5 5 0 0 0 10 0c0-2.76-2.5-5-5-3-2.5-2-5 .24-5 3Z"/><path d="M12 17a5 5 0 0 0 10 0c0-2.76-2.5-5-5-3-2.5-2-5 .24-5 3Z"/><path d="M7 14c3.22-2.91 4.29-8.75 5-12 1.66 2.38 4.94 9 5 12"/><path d="M22 9c-4.29 0-7.14-2.33-7-7-3 0-4.5 2-4.5 5"/></svg>';
$svg_beer   = '<svg class="coffee-icon coffee-icon-beer" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 11h1a3 3 0 0 1 0 6h-1"/><path d="M9 12v6"/><path d="M13 12v6"/><path d="M14 7.5c-1 0-1.44.5-3 .5s-2-.5-3-.5-1.72.5-2.5.5a2.5 2.5 0 0 1 0-5c.78 0 1.57.5 2.5.5C9.44 3.5 10 3 11 3s1.44.5 3 .5 2.5-.5 2.5-.5a2.5 2.5 0 0 1 0 5c-.78 0-1.5-.5-2.5-.5Z"/><path d="M5 8v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V8"/></svg>';

?>
<!-- wp:html -->
<div class="toolbar" role="toolbar" aria-label="<?php echo esc_attr__( 'Utilitaires du site', 'jardin' ); ?>">

	<?php if ( '' !== $lang_switch ) : ?>
	<div class="toolbar-lang">
		<?php echo $lang_switch; // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
	<?php endif; ?>

	<button class="icon-btn burger"
	        type="button"
	        id="header-burger-proxy"
	        aria-label="<?php echo esc_attr__( 'Menu', 'jardin' ); ?>"
	        aria-expanded="false"
	><?php echo $svg_burger; // phpcs:ignore WordPress.Security.EscapeOutput ?></button>

	<div class="toolbar-chrome">

		<a class="icon-btn"
		   href="<?php echo esc_url( $search_url ); ?>"
		   aria-label="<?php echo esc_attr__( 'Rechercher', 'jardin' ); ?>"
		   rel="search"
		><?php echo $svg_search; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>

		<?php echo $theme_toggle_html; // phpcs:ignore WordPress.Security.EscapeOutput ?>

		<button class="icon-btn"
		        type="button"
		        id="lfm-toggle"
		        aria-label="<?php echo esc_attr__( 'Musique en cours', 'jardin' ); ?>"
		        title="<?php echo esc_attr__( 'Musique en cours', 'jardin' ); ?>"
		        aria-pressed="false"
		><?php echo $svg_music; // phpcs:ignore WordPress.Security.EscapeOutput ?></button>

		<a class="icon-btn coffee-toggle"
		   href="<?php echo esc_url( $soutenir_url ); ?>"
		   aria-label="<?php echo esc_attr__( 'Me soutenir', 'jardin' ); ?>"
		   title="<?php echo esc_attr__( 'Me soutenir', 'jardin' ); ?>"
		><?php echo $svg_coffee . $svg_cherry . $svg_beer; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>

	</div>
</div>
<!-- /wp:html -->
