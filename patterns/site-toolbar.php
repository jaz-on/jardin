<?php
/**
 * Title: Site toolbar
 * Slug: jardin-theme/site-toolbar
 * Categories: hidden
 * Description: Header utilities — bloc langue dédié (.toolbar-lang), puis icônes segmentées (.toolbar-chrome).
 * Inserter: no
 *
 * @package Jardin_Theme */

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
		$lang_switch = '<div class="lang-switch" role="group" aria-label="' . esc_attr__( 'Langue', 'jardin-theme' ) . '">' . $buttons . '</div>';
	}
}

$svg_burger = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>';

?>
<!-- wp:html -->
<div class="toolbar" role="toolbar" aria-label="<?php echo esc_attr__( 'Utilitaires du site', 'jardin-theme' ); ?>">

	<?php if ( '' !== $lang_switch ) : ?>
	<div class="toolbar-lang">
		<?php echo $lang_switch; // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
	<?php endif; ?>

	<button class="icon-btn burger"
	        type="button"
	        id="header-burger-proxy"
	        aria-label="<?php echo esc_attr__( 'Menu', 'jardin-theme' ); ?>"
	        aria-expanded="false"
	><?php echo $svg_burger; // phpcs:ignore WordPress.Security.EscapeOutput ?></button>

	<div class="toolbar-chrome toolbar-chrome--header">
		<?php echo jardin_render_toolbar_chrome(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</div>
<!-- /wp:html -->
