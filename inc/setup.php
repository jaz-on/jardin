<?php
/**
 * Theme setup: supports, text domain, block theme basics.
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load theme textdomain and register theme supports.
 */
function jardin_setup(): void {
	load_theme_textdomain( 'jardin', get_template_directory() . '/languages' );

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
	echo '<a class="jardin-skip-link" href="#main">' . esc_html__( 'Skip to main content', 'jardin' ) . '</a>';
}
add_action( 'wp_body_open', 'jardin_skip_link', 5 );

/**
 * Theme palette toggle markup (used by the jardin/theme-toggle block).
 *
 * @return string
 */
function jardin_get_theme_toggle_markup(): string {
	$themes = array(
		array(
			'label' => __( 'Rosé Pine', 'jardin' ),
			'items' => array(
				'rose-pine'      => __( 'Main', 'jardin' ),
				'rose-pine-moon' => __( 'Moon', 'jardin' ),
				'rose-pine-dawn' => __( 'Dawn', 'jardin' ),
			),
		),
		array(
			'label' => __( 'Catppuccin', 'jardin' ),
			'items' => array(
				'catppuccin-latte'    => __( 'Latte', 'jardin' ),
				'catppuccin-frappe'   => __( 'Frappé', 'jardin' ),
				'catppuccin-macchiato' => __( 'Macchiato', 'jardin' ),
			),
		),
		array(
			'label' => __( 'Brewery', 'jardin' ),
			'items' => array(
				'brewery-pale'  => __( 'Pale ale', 'jardin' ),
				'brewery-amber' => __( 'Amber', 'jardin' ),
				'brewery-stout' => __( 'Stout', 'jardin' ),
			),
		),
	);

	ob_start();
	?>
	<details class="jardin-theme-toggle wp-block-jardin-theme-toggle">
		<summary class="jardin-theme-toggle__summary" aria-label="<?php echo esc_attr__( 'Change color theme', 'jardin' ); ?>">
			<svg class="jardin-theme-toggle__icon" width="20" height="20" aria-hidden="true" focusable="false">
				<use href="#i-palette"></use>
			</svg>
		</summary>
		<div class="jardin-theme-toggle__menu">
			<?php foreach ( $themes as $group ) : ?>
				<fieldset class="jardin-theme-toggle__fieldset">
					<legend class="jardin-theme-toggle__legend"><?php echo esc_html( $group['label'] ); ?></legend>
					<div class="jardin-theme-toggle__buttons">
						<?php foreach ( $group['items'] as $slug => $label ) : ?>
							<button type="button" class="jardin-theme-toggle__btn" data-theme-option="<?php echo esc_attr( $slug ); ?>">
								<?php echo esc_html( $label ); ?>
							</button>
						<?php endforeach; ?>
					</div>
				</fieldset>
			<?php endforeach; ?>
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

