<?php
/**
 * Dynamic styleguide shortcode.
 *
 * Renders a live inventory of the jardin-theme: design tokens from theme.json,
 * every core block with demo content, registered block styles, and all theme
 * patterns. Always in sync — nothing to maintain by hand.
 *
 * Usage: place [jardin_theme_styleguide] in the /styleguide page content.
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/**
 * Register the [jardin_theme_styleguide] shortcode.
 */
function jardin_styleguide_register(): void {
	add_shortcode( 'jardin_theme_styleguide', 'jardin_styleguide_render' );
}
add_action( 'init', 'jardin_styleguide_register' );

/**
 * Conditionally enqueue the styleguide stylesheet.
 */
function jardin_styleguide_maybe_enqueue(): void {
	global $post;
	if ( ! $post || ! has_shortcode( $post->post_content, 'jardin_theme_styleguide' ) ) {
		return;
	}
	$tpl_dir = get_template_directory();
	wp_enqueue_style(
		'jardin-theme-styleguide',
		get_template_directory_uri() . '/assets/css/styleguide.css',
		array(),
		filemtime( $tpl_dir . '/assets/css/styleguide.css' ) ?: wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'jardin_styleguide_maybe_enqueue' );

/**
 * Main render callback.
 *
 * @return string Full HTML of the styleguide.
 */
function jardin_styleguide_render(): string {
	$theme_json = jardin_sg_get_theme_json();

	ob_start();
	?>
	<div class="sg-wrap">

		<?php jardin_sg_section_toc(); ?>
		<?php jardin_sg_section_tokens( $theme_json ); ?>
		<?php jardin_sg_section_typography( $theme_json ); ?>
		<?php jardin_sg_section_spacing( $theme_json ); ?>
		<?php jardin_sg_section_blocks(); ?>
		<?php jardin_sg_section_block_styles(); ?>
		<?php jardin_sg_section_patterns(); ?>
		<?php jardin_sg_section_templates(); ?>
		<?php jardin_sg_section_cpt(); ?>

	</div>
	<?php
	return ob_get_clean();
}

/* =========================================================================
 * Helpers
 * ======================================================================= */

/**
 * Parse theme.json into an associative array.
 */
function jardin_sg_get_theme_json(): array {
	$path = get_template_directory() . '/theme.json';
	if ( ! file_exists( $path ) ) {
		return array();
	}
	$raw = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	return json_decode( $raw, true ) ?: array();
}

/**
 * Wrap a section with consistent markup.
 */
function jardin_sg_section( string $id, string $title, string $content ): void {
	printf(
		'<section class="sg-section" id="%s"><h2 class="sg-section-title">%s</h2>%s</section>',
		esc_attr( $id ),
		esc_html( $title ),
		$content
	);
}

/**
 * Wrap a single demo item.
 */
function jardin_sg_item( string $label, string $demo ): string {
	return sprintf(
		'<div class="sg-item"><p class="sg-label">%s</p><div class="sg-demo">%s</div></div>',
		esc_html( $label ),
		$demo
	);
}

/* =========================================================================
 * Table of Contents
 * ======================================================================= */

function jardin_sg_section_toc(): void {
	$sections = array(
		'sg-tokens'       => 'Design tokens (couleurs)',
		'sg-typography'   => 'Typographie',
		'sg-spacing'      => 'Spacing',
		'sg-blocks'       => 'Blocs core — démo',
		'sg-block-styles' => 'Block styles (jardin)',
		'sg-patterns'     => 'Block patterns (jardin)',
		'sg-templates'    => 'Templates & parts',
		'sg-cpt'          => 'Post types & taxonomies',
	);

	echo '<nav class="sg-toc" aria-label="Sommaire du styleguide"><h3>Sommaire</h3><ul>';
	$i = 1;
	foreach ( $sections as $id => $label ) {
		printf( '<li><a href="#%s">%d. %s</a></li>', esc_attr( $id ), $i, esc_html( $label ) );
		++$i;
	}
	echo '</ul></nav>';
}

/* =========================================================================
 * 1. Design Tokens — Colors
 * ======================================================================= */

function jardin_sg_section_tokens( array $theme_json ): void {
	$palette = $theme_json['settings']['color']['palette'] ?? array();
	if ( empty( $palette ) ) {
		return;
	}

	ob_start();
	echo '<div class="sg-color-grid">';
	foreach ( $palette as $color ) {
		$slug = $color['slug'];
		$hex  = $color['color'];
		$name = $color['name'];
		printf(
			'<div class="sg-color-swatch">'
			. '<div class="sg-color-preview" style="background-color:%s"></div>'
			. '<code class="sg-color-slug">%s</code>'
			. '<span class="sg-color-name">%s</span>'
			. '<span class="sg-color-hex">%s</span>'
			. '</div>',
			esc_attr( $hex ),
			esc_html( $slug ),
			esc_html( $name ),
			esc_html( $hex )
		);
	}
	echo '</div>';

	jardin_sg_section( 'sg-tokens', 'Design tokens (couleurs)', ob_get_clean() );
}

/* =========================================================================
 * 2. Typography
 * ======================================================================= */

function jardin_sg_section_typography( array $theme_json ): void {
	$families = $theme_json['settings']['typography']['fontFamilies'] ?? array();
	$sizes    = $theme_json['settings']['typography']['fontSizes'] ?? array();

	ob_start();

	if ( $families ) {
		echo '<h3>Font families</h3>';
		echo '<div class="sg-typo-families">';
		foreach ( $families as $family ) {
			$var = sprintf( 'var(--wp--preset--font-family--%s)', $family['slug'] );
			printf(
				'<div class="sg-typo-sample" style="font-family:%s">'
				. '<p class="sg-typo-specimen">Aa Bb Cc 123 — « jardin-theme »</p>'
				. '<code>%s</code> <span class="sg-typo-meta">%s</span>'
				. '</div>',
				esc_attr( $family['fontFamily'] ),
				esc_html( $family['slug'] ),
				esc_html( $family['fontFamily'] )
			);
		}
		echo '</div>';
	}

	if ( $sizes ) {
		echo '<h3>Font sizes</h3>';
		echo '<table class="sg-table"><thead><tr><th>Slug</th><th>Size</th><th>Aperçu</th></tr></thead><tbody>';
		foreach ( $sizes as $size ) {
			printf(
				'<tr><td><code>%s</code></td><td><code>%s</code></td><td style="font-size:%s">Le vif renard</td></tr>',
				esc_html( $size['slug'] ),
				esc_html( $size['size'] ),
				esc_attr( $size['size'] )
			);
		}
		echo '</tbody></table>';
	}

	// Heading hierarchy live demo.
	echo '<h3>Heading hierarchy</h3>';
	for ( $lvl = 1; $lvl <= 6; ++$lvl ) {
		echo do_blocks( sprintf(
			'<!-- wp:heading {"level":%d} --><h%d class="wp-block-heading">Heading %d — Le jardin typographique</h%d><!-- /wp:heading -->',
			$lvl, $lvl, $lvl, $lvl
		) );
	}

	jardin_sg_section( 'sg-typography', 'Typographie', ob_get_clean() );
}

/* =========================================================================
 * 3. Spacing
 * ======================================================================= */

function jardin_sg_section_spacing( array $theme_json ): void {
	$sizes = $theme_json['settings']['spacing']['spacingSizes'] ?? array();
	if ( empty( $sizes ) ) {
		return;
	}

	ob_start();
	echo '<div class="sg-spacing-grid">';
	foreach ( $sizes as $step ) {
		printf(
			'<div class="sg-spacing-item">'
			. '<div class="sg-spacing-bar" style="width:%s;min-width:2px"></div>'
			. '<code>%s</code> <span class="sg-spacing-val">%s</span>'
			. '</div>',
			esc_attr( $step['size'] ?: '2px' ),
			esc_html( $step['slug'] ),
			esc_html( $step['size'] )
		);
	}
	echo '</div>';

	$custom = $theme_json['settings']['custom'] ?? array();
	if ( ! empty( $custom['radius'] ) ) {
		echo '<h3>Border radius</h3><table class="sg-table"><thead><tr><th>Token</th><th>Value</th><th>Aperçu</th></tr></thead><tbody>';
		foreach ( $custom['radius'] as $token => $val ) {
			printf(
				'<tr><td><code>%s</code></td><td><code>%s</code></td><td><span class="sg-radius-demo" style="border-radius:%s"></span></td></tr>',
				esc_html( $token ),
				esc_html( $val ),
				esc_attr( $val )
			);
		}
		echo '</tbody></table>';
	}

	jardin_sg_section( 'sg-spacing', 'Spacing', ob_get_clean() );
}

/* =========================================================================
 * 4. Core Blocks — Live Demos
 * ======================================================================= */

function jardin_sg_section_blocks(): void {
	$demos = array(
		'core/paragraph' => '<!-- wp:paragraph --><p>Un paragraphe de base. Du texte avec du <strong>gras</strong>, de l\'<em>italique</em>, du <code>code inline</code>, un <a href="https://example.com">lien</a>, du <s>barré</s> et une <mark>mise en avant</mark>. Typographie française : « guillemets », apostrophes, espaces insécables — 1 234 €.</p><!-- /wp:paragraph -->',

		'core/paragraph (drop cap)' => '<!-- wp:paragraph {"dropCap":true} --><p class="has-drop-cap">La lettrine s\'active via l\'attribut dropCap du bloc paragraph. Elle est un marqueur d\'identité du thème jardin — plus grande, en serif ambré. Ce paragraphe montre le rendu réel.</p><!-- /wp:paragraph -->',

		'core/heading (h2)' => '<!-- wp:heading --><h2 class="wp-block-heading">Un heading de niveau 2</h2><!-- /wp:heading -->',

		'core/heading (h3)' => '<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Un heading de niveau 3</h3><!-- /wp:heading -->',

		'core/list' => '<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Premier élément de liste</li><!-- /wp:list-item --><!-- wp:list-item --><li>Deuxième élément avec du <strong>gras</strong></li><!-- /wp:list-item --><!-- wp:list-item --><li>Troisième avec un <a href="#">lien</a></li><!-- /wp:list-item --></ul><!-- /wp:list -->',

		'core/list (ordered)' => '<!-- wp:list {"ordered":true} --><ol class="wp-block-list"><!-- wp:list-item --><li>Première étape</li><!-- /wp:list-item --><!-- wp:list-item --><li>Deuxième étape</li><!-- /wp:list-item --><!-- wp:list-item --><li>Troisième étape</li><!-- /wp:list-item --></ol><!-- /wp:list -->',

		'core/quote' => '<!-- wp:quote --><blockquote class="wp-block-quote"><p>« Si je n\'écris pas, ça n\'existe pas. »</p><cite>Jason Rouet, à peu près tous les jours</cite></blockquote><!-- /wp:quote -->',

		'core/pullquote' => '<!-- wp:pullquote --><figure class="wp-block-pullquote"><blockquote><p>Les sites personnels bien faits sont les meilleurs endroits du web.</p><cite>Conviction personnelle</cite></blockquote></figure><!-- /wp:pullquote -->',

		'core/code' => '<!-- wp:code --><pre class="wp-block-code"><code>register_taxonomy( \'note_kind\', [ \'iwcpt_note\' ], [
  \'label\'        => \'Note Kinds\',
  \'hierarchical\' => false,
  \'public\'       => true,
] );</code></pre><!-- /wp:code -->',

		'core/preformatted' => '<!-- wp:preformatted --><pre class="wp-block-preformatted">Texte préformaté — préserve    les espaces
et les sauts
    de ligne.</pre><!-- /wp:preformatted -->',

		'core/table' => '<!-- wp:table --><figure class="wp-block-table"><table class="has-fixed-layout"><thead><tr><th>Composant</th><th>Technologie</th><th>Status</th></tr></thead><tbody><tr><td>Thème</td><td>FSE block theme</td><td>En cours</td></tr><tr><td>Plugins</td><td>PHP 8.4</td><td>Actifs</td></tr><tr><td>Hébergement</td><td>Hosterra</td><td>Production</td></tr></tbody></table></figure><!-- /wp:table -->',

		'core/separator' => '<!-- wp:separator --><hr class="wp-block-separator has-alpha-channel-opacity"/><!-- /wp:separator -->',

		'core/separator (wide)' => '<!-- wp:separator {"className":"is-style-wide"} --><hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/><!-- /wp:separator -->',

		'core/buttons' => '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Bouton primaire</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">Bouton outline</a></div><!-- /wp:button --></div><!-- /wp:buttons -->',

		'core/columns (2 cols)' => '<!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:paragraph --><p>Colonne gauche. Du texte pour voir le rendu sur deux colonnes côte à côte.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:paragraph --><p>Colonne droite. Les colonnes s\'empilent en mobile automatiquement.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns -->',

		'core/group (callout)' => '<!-- wp:group {"className":"is-style-callout-soft"} --><div class="wp-block-group is-style-callout-soft"><!-- wp:paragraph --><p>Un bloc group avec le style <code>callout-soft</code>. Utilisé pour les encadrés d\'information dans le contenu éditorial.</p><!-- /wp:paragraph --></div><!-- /wp:group -->',

		'core/details' => '<!-- wp:details --><details class="wp-block-details"><summary>Détails techniques (cliquer pour ouvrir)</summary><!-- wp:paragraph --><p>Contenu masqué par défaut, visible au clic. Utilisé pour les notes techniques, les FAQ, les avertissements.</p><!-- /wp:paragraph --></details><!-- /wp:details -->',
	);

	ob_start();
	foreach ( $demos as $label => $markup ) {
		echo jardin_sg_item( $label, do_blocks( $markup ) );
	}

	jardin_sg_section( 'sg-blocks', 'Blocs core — démo', ob_get_clean() );
}

/* =========================================================================
 * 5. Block Styles
 * ======================================================================= */

function jardin_sg_section_block_styles(): void {
	$registry = WP_Block_Styles_Registry::get_instance();
	$all      = $registry->get_all_registered();

	ob_start();
	echo '<table class="sg-table"><thead><tr><th>Bloc</th><th>Style</th><th>Label</th></tr></thead><tbody>';

	foreach ( $all as $block_name => $styles ) {
		foreach ( $styles as $style_name => $style_data ) {
			// Only show jardin styles, skip core defaults.
			if ( str_starts_with( $block_name, 'core/' ) && isset( $style_data['label'] ) ) {
				printf(
					'<tr><td><code>%s</code></td><td><code>is-style-%s</code></td><td>%s</td></tr>',
					esc_html( $block_name ),
					esc_html( $style_name ),
					esc_html( $style_data['label'] )
				);
			}
		}
	}

	echo '</tbody></table>';

	jardin_sg_section( 'sg-block-styles', 'Block styles (jardin)', ob_get_clean() );
}

/* =========================================================================
 * 6. Patterns
 * ======================================================================= */

function jardin_sg_section_patterns(): void {
	$registry = WP_Block_Patterns_Registry::get_instance();
	$all      = $registry->get_all_registered();

	$jardin_patterns = array_filter( $all, function ( $p ) {
		return str_starts_with( $p['name'], 'jardin-theme/' );
	} );

	usort( $jardin_patterns, function ( $a, $b ) {
		return strcmp( $a['name'], $b['name'] );
	} );

	ob_start();

	if ( empty( $jardin_patterns ) ) {
		echo '<p>Aucun pattern jardin-theme/ enregistré.</p>';
	}

	foreach ( $jardin_patterns as $pattern ) {
		$slug       = $pattern['name'];
		$title      = $pattern['title'] ?? $slug;
		$cats       = implode( ', ', $pattern['categories'] ?? array() );
		$is_hidden  = in_array( 'hidden', $pattern['categories'] ?? array(), true );

		echo '<div class="sg-pattern">';
		printf(
			'<div class="sg-pattern-header">'
			. '<code>%s</code>'
			. '<span class="sg-pattern-title">%s</span>'
			. '%s'
			. '</div>',
			esc_html( $slug ),
			esc_html( $title ),
			$cats ? '<span class="sg-pattern-cats">' . esc_html( $cats ) . '</span>' : ''
		);

		if ( ! $is_hidden ) {
			echo '<div class="sg-pattern-demo">';
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block content
			echo do_blocks( $pattern['content'] );
			echo '</div>';
		} else {
			echo '<p class="sg-pattern-hidden"><em>Pattern masqué (hidden) — non rendu.</em></p>';
		}

		echo '</div>';
	}

	jardin_sg_section( 'sg-patterns', 'Block patterns (jardin)', ob_get_clean() );
}

/* =========================================================================
 * 7. Templates & Template Parts
 * ======================================================================= */

function jardin_sg_section_templates(): void {
	ob_start();

	$template_dir = get_template_directory() . '/templates';
	$parts_dir    = get_template_directory() . '/parts';

	echo '<h3>Templates</h3>';
	echo '<table class="sg-table"><thead><tr><th>Fichier</th><th>Existe</th></tr></thead><tbody>';
	if ( is_dir( $template_dir ) ) {
		foreach ( glob( $template_dir . '/*.html' ) as $file ) {
			$name = basename( $file );
			printf( '<tr><td><code>templates/%s</code></td><td>✓</td></tr>', esc_html( $name ) );
		}
	}
	echo '</tbody></table>';

	echo '<h3>Template Parts</h3>';
	echo '<table class="sg-table"><thead><tr><th>Fichier</th><th>Existe</th></tr></thead><tbody>';
	if ( is_dir( $parts_dir ) ) {
		foreach ( glob( $parts_dir . '/*.html' ) as $file ) {
			$name = basename( $file );
			printf( '<tr><td><code>parts/%s</code></td><td>✓</td></tr>', esc_html( $name ) );
		}
	}
	echo '</tbody></table>';

	$theme_json = jardin_sg_get_theme_json();
	$custom_tpl = $theme_json['customTemplates'] ?? array();
	if ( $custom_tpl ) {
		echo '<h3>Custom templates (theme.json)</h3>';
		echo '<table class="sg-table"><thead><tr><th>Name</th><th>Title</th><th>Post types</th></tr></thead><tbody>';
		foreach ( $custom_tpl as $tpl ) {
			printf(
				'<tr><td><code>%s</code></td><td>%s</td><td>%s</td></tr>',
				esc_html( $tpl['name'] ),
				esc_html( $tpl['title'] ),
				esc_html( implode( ', ', $tpl['postTypes'] ?? array() ) )
			);
		}
		echo '</tbody></table>';
	}

	jardin_sg_section( 'sg-templates', 'Templates & parts', ob_get_clean() );
}

/* =========================================================================
 * 8. Post Types & Taxonomies
 * ======================================================================= */

function jardin_sg_section_cpt(): void {
	ob_start();

	$excluded_types = array( 'attachment', 'revision', 'nav_menu_item', 'wp_block',
		'wp_template', 'wp_template_part', 'wp_navigation', 'wp_font_family',
		'wp_font_face', 'wp_global_styles', 'custom_css', 'customize_changeset',
		'oembed_cache', 'user_request', 'wp_pattern',
	);

	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	echo '<h3>Post types (public)</h3>';
	echo '<table class="sg-table"><thead><tr><th>Slug</th><th>Label</th><th>Archive</th><th>Has archive</th></tr></thead><tbody>';
	foreach ( $post_types as $pt ) {
		if ( in_array( $pt->name, $excluded_types, true ) ) {
			continue;
		}
		$archive = $pt->has_archive ? ( is_string( $pt->has_archive ) ? $pt->has_archive : $pt->name ) : '—';
		printf(
			'<tr><td><code>%s</code></td><td>%s</td><td><code>%s</code></td><td>%s</td></tr>',
			esc_html( $pt->name ),
			esc_html( $pt->label ),
			esc_html( $archive ),
			$pt->has_archive ? '✓' : '—'
		);
	}
	echo '</tbody></table>';

	$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
	echo '<h3>Taxonomies (public)</h3>';
	echo '<table class="sg-table"><thead><tr><th>Slug</th><th>Label</th><th>Post types</th><th>Hierarchical</th></tr></thead><tbody>';
	foreach ( $taxonomies as $tax ) {
		printf(
			'<tr><td><code>%s</code></td><td>%s</td><td>%s</td><td>%s</td></tr>',
			esc_html( $tax->name ),
			esc_html( $tax->label ),
			esc_html( implode( ', ', $tax->object_type ) ),
			$tax->hierarchical ? '✓' : '—'
		);
	}
	echo '</tbody></table>';

	jardin_sg_section( 'sg-cpt', 'Post types & taxonomies', ob_get_clean() );
}
