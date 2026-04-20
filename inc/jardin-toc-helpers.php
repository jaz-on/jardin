<?php
/**
 * Helpers partagés : ancres des titres et rendu de la table des matières.
 *
 * @package Jardin
 * @since 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Génère la partie slug (sans préfixe heading-) à partir du HTML interne d’un titre.
 *
 * @param string $inner_html Contenu interne du tag h2–h6.
 * @return string Slug ASCII (peut être vide).
 */
function jardin_toc_slugify_heading_inner( $inner_html ) {
	$text = wp_strip_all_tags( $inner_html );
	$text = strtolower( $text );
	$text = preg_replace( '/[^a-z0-9]+/', '-', $text );
	$text = trim( $text, '-' );

	if ( strlen( $text ) > 50 ) {
		$text = substr( $text, 0, 50 );
		$text = rtrim( $text, '-' );
	}

	return $text;
}

/**
 * Produit un id d’ancre unique pour un titre, en réutilisant la logique historique (heading-…, suffixe -1, -2…).
 *
 * @param string $inner_html Contenu interne du tag h2–h6.
 * @param array  $used_ids   Référence : ids déjà attribués dans ce passage (clés = ids).
 * @return string Id HTML (sans échappement supplémentaire : utiliser esc_attr à l’affichage).
 */
function jardin_toc_unique_heading_id( $inner_html, array &$used_ids ) {
	$slug = jardin_toc_slugify_heading_inner( $inner_html );
	$id   = 'heading-' . ( '' !== $slug ? $slug : 'section' );

	$original_id = $id;
	$counter     = 1;

	while ( isset( $used_ids[ $id ] ) ) {
		$id = $original_id . '-' . $counter;
		++$counter;
	}

	$used_ids[ $id ] = true;

	return $id;
}

/**
 * Extrait les entrées de table des matières depuis une chaîne HTML (contenu brut ou rendu).
 *
 * @param string $content         HTML contenant des h2–h6.
 * @param array  $heading_levels  Niveaux à inclure (entiers 2–6).
 * @return array<int, array{level: int, id: string, text: string}>
 */
function jardin_toc_extract_from_html( $content, array $heading_levels ) {
	$pattern = '/<h([2-6])([^>]*)>(.*?)<\/h[2-6]>/is';
	if ( ! preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER ) ) {
		return array();
	}

	$used_ids = array();
	$toc      = array();

	foreach ( $matches as $match ) {
		$level = (int) $match[1];
		if ( ! in_array( $level, $heading_levels, true ) ) {
			continue;
		}

		$attrs = $match[2];
		$inner = $match[3];
		$text  = wp_strip_all_tags( $inner );

		if ( '' === $text ) {
			continue;
		}

		if ( preg_match( '/\bid\s*=\s*["\']([^"\']+)["\']/', $attrs, $id_match ) ) {
			$id = $id_match[1];
			if ( ! isset( $used_ids[ $id ] ) ) {
				$used_ids[ $id ] = true;
			}
		} else {
			$id = jardin_toc_unique_heading_id( $inner, $used_ids );
		}

		$toc[] = array(
			'level' => $level,
			'id'    => $id,
			'text'  => $text,
		);
	}

	return $toc;
}

/**
 * Rend la liste interne de la table des matières (sans wrapper ul/ol racine ; placée dans le ul/ol du template).
 *
 * @param array  $toc  Entrées {level, id, text}.
 * @param string $tag  ul ou ol pour les sous-listes.
 * @return string HTML fragment.
 */
function jardin_render_toc_list( array $toc, $tag = 'ul' ) {
	if ( empty( $toc ) ) {
		return '';
	}

	$tag = ( 'ol' === $tag ) ? 'ol' : 'ul';

	$item_open = static function ( $item ) {
		$level = (int) $item['level'];
		$id    = esc_attr( $item['id'] );
		$text  = esc_html( $item['text'] );

		return '<li class="jardin-toc__item jardin-toc__item--level-' . $level . '"><a href="#' . $id . '" class="jardin-toc__link">' . $text . '</a>';
	};

	$html  = $item_open( $toc[0] );
	$count = count( $toc );

	for ( $i = 1; $i < $count; $i++ ) {
		$prev_level = (int) $toc[ $i - 1 ]['level'];
		$level      = (int) $toc[ $i ]['level'];

		if ( $level > $prev_level ) {
			$html .= '<' . $tag . ' class="jardin-toc__sublist">' . $item_open( $toc[ $i ] );
		} elseif ( $level === $prev_level ) {
			$html .= '</li>' . $item_open( $toc[ $i ] );
		} else {
			for ( $j = $prev_level; $j > $level; $j-- ) {
				$html .= '</li></' . $tag . '>';
			}
			$html .= '</li>' . $item_open( $toc[ $i ] );
		}
	}

	$last_level  = (int) $toc[ $count - 1 ]['level'];
	$first_level = (int) $toc[0]['level'];

	$html .= '</li>';
	for ( $j = $last_level; $j > $first_level; $j-- ) {
		$html .= '</' . $tag . '>';
	}

	return $html;
}
