<?php
/**
 * Header toolbar & mobile drawer utilities — single source of truth for markup.
 *
 * Rendered by the dynamic block {@see jardin_register_theme_blocks()} `jardin-theme/header-utilities` (patterns emit only this block; no `core/html` fallback).
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Resolve support link from block attribute (path like /soutenir/ or full URL).
 *
 * @param string $raw Attribute value; empty defaults to /soutenir/.
 * @return string Unescaped URL (pass through esc_url() in markup).
 */
function jardin_resolve_toolbar_support_url( string $raw ): string {
	$raw = trim( $raw );
	if ( '' === $raw ) {
		$raw = '/soutenir/';
	}
	if ( preg_match( '#^https?://#i', $raw ) ) {
		return $raw;
	}
	$path = '/' . trim( $raw, '/' ) . '/';
	return trailingslashit( home_url( $path ) );
}

/**
 * Polylang language switcher markup (empty when Polylang is absent or has no languages).
 *
 * @return string
 */
function jardin_get_toolbar_language_markup(): string {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return '';
	}
	$langs = pll_the_languages( array( 'raw' => 1 ) );
	if ( empty( $langs ) ) {
		return '';
	}
	$buttons = '';
	foreach ( $langs as $lang ) {
		$current = ! empty( $lang['current_lang'] );
		$buttons  .= sprintf(
			'<a href="%s" lang="%s" hreflang="%s"%s>%s</a>',
			esc_url( $lang['url'] ),
			esc_attr( $lang['slug'] ),
			esc_attr( $lang['slug'] ),
			$current ? ' aria-current="true"' : '',
			esc_html( strtoupper( $lang['slug'] ) )
		);
	}
	return '<div class="lang-switch" role="group" aria-label="' . esc_attr__( 'Language', 'jardin-theme' ) . '">' . $buttons . '</div>';
}

/**
 * SVG icons for toolbar chrome (search, LFM, support multi-icon).
 *
 * @return array{search:string,music:string,coffee:string}
 */
function jardin_get_toolbar_chrome_svgs(): array {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array(
		'search' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>',
		'music'  => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>',
		'coffee' => '<svg class="coffee-icon coffee-icon-coffee" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 2v2"/><path d="M14 2v2"/><path d="M16 8a1 1 0 0 1 1 1v8a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V9a1 1 0 0 1 1-1h14a4 4 0 1 1 0 8h-1"/><path d="M6 2v2"/></svg>'
			. '<svg class="coffee-icon coffee-icon-cherry" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 17a5 5 0 0 0 10 0c0-2.76-2.5-5-5-3-2.5-2-5 .24-5 3Z"/><path d="M12 17a5 5 0 0 0 10 0c0-2.76-2.5-5-5-3-2.5-2-5 .24-5 3Z"/><path d="M7 14c3.22-2.91 4.29-8.75 5-12 1.66 2.38 4.94 9 5 12"/><path d="M22 9c-4.29 0-7.14-2.33-7-7-3 0-4.5 2-4.5 5"/></svg>'
			. '<svg class="coffee-icon coffee-icon-beer" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 11h1a3 3 0 0 1 0 6h-1"/><path d="M9 12v6"/><path d="M13 12v6"/><path d="M14 7.5c-1 0-1.44.5-3 .5s-2-.5-3-.5-1.72.5-2.5.5a2.5 2.5 0 0 1 0-5c.78 0 1.57.5 2.5.5C9.44 3.5 10 3 11 3s1.44.5 3 .5 2.5-.5 2.5-.5a2.5 2.5 0 0 1 0 5c-.78 0-1.5-.5-2.5-.5Z"/><path d="M5 8v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V8"/></svg>',
	);
	return $cache;
}

/**
 * Burger icon (mobile menu proxy).
 *
 * @return string
 */
function jardin_get_toolbar_burger_svg(): string {
	return '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>';
}

/**
 * Inner chrome: search, theme picker, LFM toggle, support link (no outer wrapper).
 *
 * @param string $chrome_class `toolbar-chrome--header` or `toolbar-chrome--drawer`.
 * @param bool   $wrap_group   When true, add role="group" and aria-label (drawer row).
 * @param string $support_url_raw Support link path or URL (from block attribute); empty uses /soutenir/.
 * @return string
 */
function jardin_get_toolbar_chrome_inner_markup( string $chrome_class, bool $wrap_group = false, string $support_url_raw = '' ): string {
	$svgs        = jardin_get_toolbar_chrome_svgs();
	$search_url  = home_url( '/?s=' );
	$support_url = jardin_resolve_toolbar_support_url( $support_url_raw );
	$theme       = function_exists( 'jardin_get_theme_toggle_markup' ) ? jardin_get_theme_toggle_markup() : '';

	ob_start();
	?>
	<div class="toolbar-chrome <?php echo esc_attr( $chrome_class ); ?>"
		<?php if ( $wrap_group ) : ?>
		role="group" aria-label="<?php echo esc_attr__( 'Search, theme, music, and support', 'jardin-theme' ); ?>"
		<?php endif; ?>
	>
		<a class="icon-btn"
		   href="<?php echo esc_url( $search_url ); ?>"
		   aria-label="<?php echo esc_attr__( 'Search', 'jardin-theme' ); ?>"
		   rel="search"
		><?php echo $svgs['search']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		<?php echo $theme; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<button class="icon-btn lfm-toolbar-toggle"
		        type="button"
		        aria-label="<?php echo esc_attr__( 'Now playing', 'jardin-theme' ); ?>"
		        title="<?php echo esc_attr__( 'Now playing', 'jardin-theme' ); ?>"
		        aria-pressed="false"
		><?php echo $svgs['music']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
		<a class="icon-btn coffee-toggle"
		   href="<?php echo esc_url( $support_url ); ?>"
		   aria-label="<?php echo esc_attr__( 'Support me', 'jardin-theme' ); ?>"
		   title="<?php echo esc_attr__( 'Support me', 'jardin-theme' ); ?>"
		><?php echo $svgs['coffee']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Header row: language, burger, chrome (desktop / collapsed chrome on small screens per CSS).
 *
 * @param string $support_url_raw Support link (block attribute).
 * @return string
 */
function jardin_get_header_utilities_header_markup( string $support_url_raw = '' ): string {
	$lang = jardin_get_toolbar_language_markup();
	$svg_burger = jardin_get_toolbar_burger_svg();
	$chrome     = jardin_get_toolbar_chrome_inner_markup( 'toolbar-chrome--header', false, $support_url_raw );

	ob_start();
	?>
	<div class="toolbar" role="toolbar" aria-label="<?php echo esc_attr__( 'Site utilities', 'jardin-theme' ); ?>">
		<?php if ( '' !== $lang ) : ?>
		<div class="toolbar-lang">
			<?php echo $lang; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php endif; ?>
		<button class="icon-btn burger"
		        type="button"
		        id="header-burger-proxy"
		        aria-label="<?php echo esc_attr__( 'Menu', 'jardin-theme' ); ?>"
		        aria-expanded="false"
		><?php echo $svg_burger; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
		<?php echo $chrome; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Mobile nav drawer: heading + same chrome actions as header.
 *
 * @param string $support_url_raw Support link (block attribute).
 * @return string
 */
function jardin_get_header_utilities_drawer_markup( string $support_url_raw = '' ): string {
	$chrome = jardin_get_toolbar_chrome_inner_markup( 'toolbar-chrome--drawer', true, $support_url_raw );

	ob_start();
	?>
	<div class="site-nav-drawer-tools" role="region" aria-labelledby="site-nav-drawer-tools-heading">
		<p class="site-nav-drawer-tools__heading" id="site-nav-drawer-tools-heading"><?php echo esc_html__( 'Tools', 'jardin-theme' ); ?></p>
		<?php echo $chrome; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<?php
	return (string) ob_get_clean();
}
