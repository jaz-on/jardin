<?php
/**
 * Shared header toolbar chrome (search, theme, music, support).
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/**
 * Inner HTML for toolbar chrome: search link, theme toggle, music toggle, support link.
 *
 * @return string HTML (no outer .toolbar-chrome wrapper).
 */
function jardin_render_toolbar_chrome() {
	$theme_toggle_html = '';
	if ( function_exists( 'do_blocks' ) ) {
		$theme_toggle_html = do_blocks( '<!-- wp:jardin-theme/theme-toggle /-->' );
	}
	if ( '' === trim( wp_strip_all_tags( (string) $theme_toggle_html ) ) && function_exists( 'jardin_get_theme_toggle_markup' ) ) {
		$theme_toggle_html = jardin_get_theme_toggle_markup();
	}

	$search_url   = home_url( '/?s=' );
	$soutenir_url = home_url( '/soutenir/' );

	$svg_search = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>';
	$svg_music  = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>';
	$svg_coffee = '<svg class="coffee-icon coffee-icon-coffee" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 2v2"/><path d="M14 2v2"/><path d="M16 8a1 1 0 0 1 1 1v8a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V9a1 1 0 0 1 1-1h14a4 4 0 1 1 0 8h-1"/><path d="M6 2v2"/></svg>';
	$svg_cherry = '<svg class="coffee-icon coffee-icon-cherry" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 17a5 5 0 0 0 10 0c0-2.76-2.5-5-5-3-2.5-2-5 .24-5 3Z"/><path d="M12 17a5 5 0 0 0 10 0c0-2.76-2.5-5-5-3-2.5-2-5 .24-5 3Z"/><path d="M7 14c3.22-2.91 4.29-8.75 5-12 1.66 2.38 4.94 9 5 12"/><path d="M22 9c-4.29 0-7.14-2.33-7-7-3 0-4.5 2-4.5 5"/></svg>';
	$svg_beer   = '<svg class="coffee-icon coffee-icon-beer" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 11h1a3 3 0 0 1 0 6h-1"/><path d="M9 12v6"/><path d="M13 12v6"/><path d="M14 7.5c-1 0-1.44.5-3 .5s-2-.5-3-.5-1.72.5-2.5.5a2.5 2.5 0 0 1 0-5c.78 0 1.57.5 2.5.5C9.44 3.5 10 3 11 3s1.44.5 3 .5 2.5-.5 2.5-.5a2.5 2.5 0 0 1 0 5c-.78 0-1.5-.5-2.5-.5Z"/><path d="M5 8v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V8"/></svg>';

	ob_start();
	?>
	<a class="icon-btn"
	   href="<?php echo esc_url( $search_url ); ?>"
	   aria-label="<?php echo esc_attr__( 'Rechercher', 'jardin-theme' ); ?>"
	   rel="search"
	><?php echo $svg_search; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>

	<?php echo $theme_toggle_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<button class="icon-btn lfm-toolbar-toggle"
	        type="button"
	        aria-label="<?php echo esc_attr__( 'Musique en cours', 'jardin-theme' ); ?>"
	        title="<?php echo esc_attr__( 'Musique en cours', 'jardin-theme' ); ?>"
	        aria-pressed="false"
	><?php echo $svg_music; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>

	<a class="icon-btn coffee-toggle"
	   href="<?php echo esc_url( $soutenir_url ); ?>"
	   aria-label="<?php echo esc_attr__( 'Me soutenir', 'jardin-theme' ); ?>"
	   title="<?php echo esc_attr__( 'Me soutenir', 'jardin-theme' ); ?>"
	><?php echo $svg_coffee . $svg_cherry . $svg_beer; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	<?php
	return (string) ob_get_clean();
}
