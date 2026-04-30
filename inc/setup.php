<?php
/**
 * Theme setup: supports, text domain, block theme basics.
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/**
 * Load theme textdomain and register theme supports.
 */
function jardin_setup(): void {
	load_theme_textdomain( 'jardin-theme', get_template_directory() . '/languages' );

	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/theme-base.css' );
	add_editor_style( 'assets/themes/all.css' );

	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'jardin_setup' );

/**
 * Skip link for keyboard and screen reader users (WCAG 2.4.1).
 */
function jardin_skip_link(): void {
	echo '<a class="jardin-theme-skip-link" href="#main">' . esc_html__( 'Skip to main content', 'jardin-theme' ) . '</a>';
}
add_action( 'wp_body_open', 'jardin_skip_link', 5 );

/**
 * Theme palette toggle markup (used by the jardin-theme/theme-toggle block).
 *
 * @return string
 */
function jardin_get_theme_toggle_markup(): string {
	$themes = array(
		array(
			'label' => __( 'Rosé Pine', 'jardin-theme' ),
			'desc'  => __( 'Minimal Soho aesthetic', 'jardin-theme' ),
			'items' => array(
				array( 'slug' => 'rose-pine-dawn', 'name' => __( 'Dawn', 'jardin-theme' ),  'icon' => '🌅', 'swatches' => array( '#faf4ed', '#d7827e', '#575279' ) ),
				array( 'slug' => 'rose-pine-moon', 'name' => __( 'Moon', 'jardin-theme' ),  'icon' => '🌙', 'swatches' => array( '#232136', '#ea9a97', '#e0def4' ) ),
				array( 'slug' => 'rose-pine',      'name' => __( 'Main', 'jardin-theme' ),  'icon' => '🌑', 'swatches' => array( '#191724', '#ebbcba', '#e0def4' ) ),
			),
		),
		array(
			'label' => __( 'Catppuccin', 'jardin-theme' ),
			'desc'  => __( 'Pastel café tones', 'jardin-theme' ),
			'items' => array(
				array( 'slug' => 'catppuccin-latte',     'name' => __( 'Latte', 'jardin-theme' ),     'icon' => '🥛', 'swatches' => array( '#eff1f5', '#df8e1d', '#4c4f69' ) ),
				array( 'slug' => 'catppuccin-frappe',     'name' => __( 'Frappé', 'jardin-theme' ),    'icon' => '🍮', 'swatches' => array( '#303446', '#e5c890', '#c6d0f5' ) ),
				array( 'slug' => 'catppuccin-macchiato', 'name' => __( 'Macchiato', 'jardin-theme' ), 'icon' => '☕', 'swatches' => array( '#24273a', '#eed49f', '#cad3f5' ) ),
			),
		),
		array(
			'label' => __( 'Brewery', 'jardin-theme' ),
			'desc'  => __( 'Craft beer palettes', 'jardin-theme' ),
			'items' => array(
			array( 'slug' => 'brewery-pale',  'name' => __( 'Pale ale', 'jardin-theme' ), 'icon' => '🍺', 'swatches' => array( '#FBF6E9', '#B8772A', '#2C2418' ) ),
			array( 'slug' => 'brewery-amber', 'name' => __( 'IPA', 'jardin-theme' ),      'icon' => '🍻', 'swatches' => array( '#3A2210', '#C87533', '#F0D9B5' ) ),
			array( 'slug' => 'brewery-stout', 'name' => __( 'Stout', 'jardin-theme' ),    'icon' => '🥃', 'swatches' => array( '#1F1611', '#D9A441', '#F4E4CC' ) ),
			),
		),
	);

	ob_start();
	?>
	<details class="jardin-theme-toggle wp-block-jardin-theme-theme-toggle">
		<summary class="jardin-theme-toggle__summary" aria-label="<?php echo esc_attr__( 'Change color theme', 'jardin-theme' ); ?>">
			<svg class="jardin-theme-toggle__icon" width="20" height="20" aria-hidden="true" focusable="false">
				<use href="#i-palette"></use>
			</svg>
		</summary>
		<div class="jardin-theme-toggle__menu" role="dialog" aria-label="<?php echo esc_attr__( 'Theme selection', 'jardin-theme' ); ?>">
			<div class="jardin-theme-toggle__header">
				<h2 class="jardin-theme-toggle__title"><?php echo esc_html__( 'Color scheme', 'jardin-theme' ); ?></h2>
				<button type="button" class="jardin-theme-toggle__close" aria-label="<?php echo esc_attr__( 'Close', 'jardin-theme' ); ?>">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
				</button>
			</div>
			<div class="jardin-theme-toggle__body">
			<?php foreach ( $themes as $group ) : ?>
				<fieldset class="jardin-theme-toggle__family">
					<legend><?php echo esc_html( $group['label'] ); ?> <span class="jardin-theme-toggle__family-desc"><?php echo esc_html( $group['desc'] ); ?></span></legend>
					<ul class="jardin-theme-toggle__list">
						<?php foreach ( $group['items'] as $item ) : ?>
						<li>
							<button type="button" class="jardin-theme-toggle__option" data-theme-option="<?php echo esc_attr( $item['slug'] ); ?>">
								<span class="jardin-theme-toggle__option-icon"><?php echo $item['icon']; // phpcs:ignore ?></span>
								<span class="jardin-theme-toggle__option-name"><?php echo esc_html( $item['name'] ); ?></span>
								<span class="jardin-theme-toggle__swatches">
									<?php foreach ( $item['swatches'] as $color ) : ?>
									<span style="background:<?php echo esc_attr( $color ); ?>"></span>
									<?php endforeach; ?>
								</span>
							</button>
						</li>
						<?php endforeach; ?>
					</ul>
				</fieldset>
			<?php endforeach; ?>
			</div>
			<div class="jardin-theme-toggle__footer">
				<button type="button" class="jardin-theme-toggle__system" data-theme-system>
					<?php echo esc_html__( 'Match system', 'jardin-theme' ); ?>
				</button>
			</div>
		</div>
	</details>
	<?php
	return (string) ob_get_clean();
}

/**
 * Inline boot script to reduce theme FOUC (runs before body).
 */
function jardin_inline_theme_boot(): void {
	$valid = array(
		'rose-pine',
		'rose-pine-moon',
		'rose-pine-dawn',
		'catppuccin-latte',
		'catppuccin-frappe',
		'catppuccin-macchiato',
		'brewery-pale',
		'brewery-amber',
		'brewery-stout',
	);
	$list  = wp_json_encode( $valid );
	$inline = <<<JS
<script>
(function(){try{var k='jardin-theme',ok={$list},t=localStorage.getItem(k),a=document.documentElement;
if(t&&ok.indexOf(t)>-1){a.setAttribute('data-theme',t);return}
var d=window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches;
a.setAttribute('data-theme',d?'rose-pine':'catppuccin-latte')}catch(e){}})();
</script>
JS;
	echo $inline; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'jardin_inline_theme_boot', 0 );

/**
 * Load SVG sprite on the front (for palette icon / future icons).
 */
function jardin_inline_sprite(): void {
	$path = get_template_directory() . '/assets/icons/sprite.svg';
	if ( ! is_readable( $path ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo file_get_contents( $path );
}
add_action( 'wp_body_open', 'jardin_inline_sprite', 1 );

