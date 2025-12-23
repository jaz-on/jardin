<?php
/**
 * Template de rendu pour le bloc Table of Contents.
 *
 * @package Jardin
 * @since 0.1.0
 *
 * @var array    $attributes Attributs du bloc.
 * @var string   $content Contenu du bloc.
 * @var WP_Block $block Instance du bloc.
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Génère la table des matières à partir du contenu du post.
 *
 * @param array $heading_levels Niveaux de titres à inclure (ex: [2, 3, 4]).
 * @return array Table des matières structurée.
 */
function jardin_generate_toc( $heading_levels ) {
	global $post;

	if ( ! $post ) {
		return array();
	}

	$content = $post->post_content;
	$toc     = array();

	// Pattern pour matcher les titres avec leurs IDs.
	$pattern = '/<h([2-6])([^>]*id=["\']([^"\']+)["\'][^>]*)>(.*?)<\/h[2-6]>/i';

	preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER );

	foreach ( $matches as $match ) {
		$level = (int) $match[1];
		$id    = $match[3];
		$text  = wp_strip_all_tags( $match[4] );

		// Inclure seulement les niveaux demandés.
		if ( in_array( $level, $heading_levels, true ) ) {
			$toc[] = array(
				'level' => $level,
				'id'    => $id,
				'text'  => $text,
			);
		}
	}

	return $toc;
}

/**
 * Rend la liste de la table des matières.
 *
 * @param array  $toc Table des matières.
 * @param string $tag Tag HTML (ul ou ol).
 * @return string HTML de la liste.
 */
function jardin_render_toc_list( $toc, $tag = 'ul' ) {
	if ( empty( $toc ) ) {
		return '';
	}

	$output       = '';
	$current_level = 0;

	foreach ( $toc as $item ) {
		$level = $item['level'];
		$id    = esc_attr( $item['id'] );
		$text  = esc_html( $item['text'] );

		// Fermer les listes imbriquées si nécessaire.
		if ( $current_level > 0 && $level > $current_level ) {
			// Ouvrir une nouvelle liste imbriquée.
			$output .= '<' . $tag . ' class="jardin-toc__sublist">';
		} elseif ( $current_level > 0 && $level < $current_level ) {
			// Fermer les listes imbriquées.
			$diff = $current_level - $level;
			for ( $i = 0; $i < $diff; $i++ ) {
				$output .= '</' . $tag . '>';
			}
		} elseif ( $current_level > 0 && $level === $current_level ) {
			// Même niveau, fermer l'élément précédent.
			$output .= '</li>';
		}

		// Ouvrir un nouvel élément de liste.
		$output .= '<li class="jardin-toc__item jardin-toc__item--level-' . $level . '">';
		$output .= '<a href="#' . $id . '" class="jardin-toc__link">' . $text . '</a>';

		$current_level = $level;
	}

	// Fermer toutes les listes ouvertes.
	while ( $current_level > 0 ) {
		$output .= '</li>';
		$output .= '</' . $tag . '>';
		$current_level--;
	}

	return $output;
}

// Récupérer et valider les attributs.
$heading_levels = isset( $attributes['headingLevels'] ) && is_array( $attributes['headingLevels'] ) ? $attributes['headingLevels'] : array( 2, 3, 4, 5, 6 );

// Ne garder que les niveaux valides entre 2 et 6.
$heading_levels = array_values(
	array_filter(
		$heading_levels,
		static function( $level ) {
			$level = (int) $level;
			return $level >= 2 && $level <= 6;
		}
	)
);

if ( empty( $heading_levels ) ) {
	$heading_levels = array( 2, 3, 4, 5, 6 );
}

$show_title = isset( $attributes['showTitle'] ) ? (bool) $attributes['showTitle'] : true;

// Le texte est échappé au moment de l'affichage.
$custom_title = isset( $attributes['customTitle'] ) && is_string( $attributes['customTitle'] )
	? $attributes['customTitle']
	: __( 'Table of Contents', 'jardin' );

$list_style = isset( $attributes['listStyle'] ) && in_array( $attributes['listStyle'], array( 'ul', 'ol' ), true )
	? $attributes['listStyle']
	: 'ul';

// Générer la table des matières.
$toc = jardin_generate_toc( $heading_levels );

if ( empty( $toc ) ) {
	return;
}

// Classes du wrapper.
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'jardin-toc',
	)
);
?>

<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( $show_title ) : ?>
		<h2 class="jardin-toc__title"><?php echo esc_html( $custom_title ); ?></h2>
	<?php endif; ?>

	<?php if ( 'ul' === $list_style ) : ?>
		<ul class="jardin-toc__list">
			<?php
			echo wp_kses(
				jardin_render_toc_list( $toc, 'ul' ),
				array(
					'ul' => array(
						'class' => true,
					),
					'ol' => array(
						'class' => true,
					),
					'li' => array(
						'class' => true,
					),
					'a'  => array(
						'href'  => true,
						'class' => true,
					),
				)
			);
			?>
		</ul>
	<?php else : ?>
		<ol class="jardin-toc__list">
			<?php
			echo wp_kses(
				jardin_render_toc_list( $toc, 'ol' ),
				array(
					'ul' => array(
						'class' => true,
					),
					'ol' => array(
						'class' => true,
					),
					'li' => array(
						'class' => true,
					),
					'a'  => array(
						'href'  => true,
						'class' => true,
					),
				)
			);
			?>
		</ol>
	<?php endif; ?>
</div>

