<?php
/**
 * Jardin functions and definitions
 *
 * @package Jardin
 * @since 0.1.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define theme constants.
define( 'JARDIN_VERSION', '0.1.0' );

/**
 * Theme setup.
 */
if ( ! function_exists( 'jardin_support' ) ) {
	/**
	 * Ajoute les supports de thème WordPress.
	 */
	function jardin_support() {
		// Chargement du domaine de traduction.
		load_theme_textdomain( 'jardin', get_template_directory() . '/languages' );

		// Support des styles de blocs WordPress.
		add_theme_support( 'wp-block-styles' );

		// Support du style de l'éditeur.
		add_editor_style( 'style.css' );

		// Support des images mises en avant.
		add_theme_support( 'post-thumbnails' );

		// Support des titres automatiques.
		add_theme_support( 'title-tag' );

		// Support des formats de posts.
		add_theme_support( 'post-formats', array( 'aside', 'gallery', 'quote', 'image', 'video' ) );
	}
}
add_action( 'after_setup_theme', 'jardin_support' );

/**
 * Preload critical fonts.
 */
if ( ! function_exists( 'jardin_preload_fonts' ) ) {
	/**
	 * Preload critical fonts for better performance.
	 */
	function jardin_preload_fonts() {
		$theme_dir      = get_template_directory_uri();
		$theme_dir_path = get_template_directory();

		$fonts_to_preload = array(
			array(
				'path' => '/assets/fonts/inter/InterVariable.woff2',
				'type' => 'font/woff2',
			),
			array(
				'path' => '/assets/fonts/calligraffitti/Calligraffitti-Regular.woff2',
				'type' => 'font/woff2',
			),
			array(
				'path' => '/assets/fonts/atkinson-hyperlegible/AtkinsonHyperlegible-Regular.woff2',
				'type' => 'font/woff2',
			),
		);

		foreach ( $fonts_to_preload as $font ) {
			$file_path = $theme_dir_path . $font['path'];

			if ( ! file_exists( $file_path ) ) {
				continue;
			}

			printf(
				'<link rel="preload" href="%1$s" as="font" type="%2$s" crossorigin />' . "\n",
				esc_url( $theme_dir . $font['path'] ),
				esc_attr( $font['type'] )
			);
		}
	}
}
add_action( 'wp_head', 'jardin_preload_fonts', 1 );

/**
 * Check for missing assets and log errors.
 */
if ( ! function_exists( 'jardin_check_assets' ) ) {
	/**
	 * Vérifie la présence des assets critiques.
	 */
	function jardin_check_assets() {
		$theme_dir = get_template_directory();
		$required_fonts = array(
			'/assets/fonts/inter/InterVariable.woff2',
			'/assets/fonts/inter/InterVariable-Italic.woff2',
			'/assets/fonts/calligraffitti/Calligraffitti-Regular.woff2',
			'/assets/fonts/calligraffitti/Calligraffitti-Regular.ttf', // Fallback
			'/assets/fonts/fira-code/FiraCode-VF.woff2',
			'/assets/fonts/atkinson-hyperlegible/AtkinsonHyperlegible-Regular.woff2',
			'/assets/fonts/atkinson-hyperlegible/AtkinsonHyperlegible-Bold.woff2',
			'/assets/fonts/atkinson-hyperlegible/AtkinsonHyperlegible-Italic.woff2',
			'/assets/fonts/atkinson-hyperlegible/AtkinsonHyperlegible-BoldItalic.woff2',
		);
		
		foreach ( $required_fonts as $font ) {
			if ( ! file_exists( $theme_dir . $font ) ) {
				// Log error for developers
				error_log( sprintf( 'Jardin Theme: Missing font file - %s', $font ) );
				
				// Add admin notice for logged-in users
				if ( current_user_can( 'manage_options' ) ) {
					add_action(
						'admin_notices',
						function() use ( $font ) {
							?>
							<div class="notice notice-warning">
								<p>
									<strong><?php esc_html_e( 'Jardin Theme:', 'jardin' ); ?></strong>
									<?php
									printf(
										/* translators: %s: font file name. */
										esc_html__( 'Missing font file: %s', 'jardin' ),
										esc_html( basename( $font ) )
									);
									?>
								</p>
							</div>
							<?php
						}
					);
				}
			}
		}
	}
}
add_action( 'after_setup_theme', 'jardin_check_assets' );

/**
 * Enqueue styles with improved versioning.
 */
if ( ! function_exists( 'jardin_styles' ) ) {
	/**
	 * Charge les styles du thème avec versioning optimisé.
	 */
	function jardin_styles() {
		$theme_dir  = get_template_directory();
		$style_file = $theme_dir . '/style.css';
		$css_file   = $theme_dir . '/assets/css/theme-styles.css';
		
		// Use file modification time for better cache busting.
		$version = file_exists( $style_file )
			? filemtime( $style_file )
			: JARDIN_VERSION;
		
		wp_enqueue_style(
			'jardin-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$version
		);

		// Styles complémentaires du thème (effets, ToC, etc.).
		if ( file_exists( $css_file ) ) {
			wp_enqueue_style(
				'jardin-theme-styles',
				get_template_directory_uri() . '/assets/css/theme-styles.css',
				array( 'jardin-style' ),
				filemtime( $css_file )
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'jardin_styles' );

/**
 * Register block style variations.
 */
if ( ! function_exists( 'jardin_register_block_styles' ) ) {
	/**
	 * Enregistre les variantes de styles pour les blocs.
	 */
	function jardin_register_block_styles() {
		// Variante "Butter Effect" pour le site-title
		register_block_style(
			'core/site-title',
			array(
				'name'         => 'butter-effect',
				'label'        => __( 'Butter Effect', 'jardin' ),
				'is_default'   => true,
			)
		);
	}
}
add_action( 'init', 'jardin_register_block_styles' );

/**
 * Vérifie si le plugin Jardin Events est disponible.
 *
 * @return bool
 */
function jardin_events_available() {
	return function_exists( 'jardin_events_is_active' ) && jardin_events_is_active();
}

/**
 * Marque le thème comme récemment activé pour afficher une notice d'information.
 */
function jardin_theme_activated() {
	update_option( 'jardin_show_events_notice', 1 );
}
add_action( 'after_switch_theme', 'jardin_theme_activated' );

/**
 * Affiche une notice suggérant l'installation du plugin Jardin Events.
 */
function jardin_events_admin_notice() {
	// Ne rien faire si la notice est désactivée.
	if ( ! get_option( 'jardin_show_events_notice' ) ) {
		return;
	}

	// Si le plugin est actif, on supprime la notice.
	if ( jardin_events_available() ) {
		delete_option( 'jardin_show_events_notice' );
		return;
	}
	?>
	<div class="notice notice-info is-dismissible">
		<p>
			<strong><?php esc_html_e( 'Thème Jardin', 'jardin' ); ?></strong>
			<?php esc_html_e( 'Pour activer le système d’événements, installez et activez le plugin Jardin Events.', 'jardin' ); ?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'jardin_events_admin_notice' );

/**
 * Charger les classes PHP.
 */
require_once get_template_directory() . '/inc/class-jardin-blocks.php';
require_once get_template_directory() . '/inc/class-jardin-toc.php';

