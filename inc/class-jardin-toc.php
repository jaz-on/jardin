<?php
/**
 * Class Jardin_TOC
 *
 * Gestion de la table des matières : génération d'IDs pour les titres.
 *
 * @package Jardin
 * @since 0.1.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe pour la gestion de la table des matières.
 */
class Jardin_TOC {

	/**
	 * Constructeur.
	 */
	public function __construct() {
		add_filter( 'the_content', array( $this, 'add_ids_to_headings' ), 10, 1 );
	}

	/**
	 * Ajoute des IDs uniques aux titres (H2-H6) dans le contenu.
	 *
	 * @param string $content Contenu du post.
	 * @return string Contenu modifié avec IDs ajoutés.
	 */
	public function add_ids_to_headings( $content ) {
		if ( ! is_singular() ) {
			return $content;
		}

		// Pattern pour matcher les titres H2-H6.
		$pattern = '/<h([2-6])([^>]*)>(.*?)<\/h[2-6]>/i';

		$content = preg_replace_callback(
			$pattern,
			array( $this, 'add_id_to_heading' ),
			$content
		);

		return $content;
	}

	/**
	 * Callback pour ajouter un ID à un titre.
	 *
	 * @param array $matches Matches du preg_replace_callback.
	 * @return string Titre avec ID ajouté.
	 */
	private function add_id_to_heading( $matches ) {
		$level    = $matches[1];
		$attrs    = $matches[2];
		$text     = $matches[3];
		$id_value = $this->generate_id_from_text( $text );

		// Vérifier si un ID existe déjà.
		if ( preg_match( '/id=["\']([^"\']+)["\']/', $attrs, $id_match ) ) {
			return $matches[0]; // ID déjà présent, ne rien faire.
		}

		// Ajouter l'ID.
		$id_attr = ' id="' . esc_attr( $id_value ) . '"';
		$new_tag = '<h' . $level . $attrs . $id_attr . '>' . $text . '</h' . $level . '>';

		return $new_tag;
	}

	/**
	 * Génère un ID à partir du texte d'un titre.
	 *
	 * @param string $text Texte du titre.
	 * @return string ID généré.
	 */
	private function generate_id_from_text( $text ) {
		// Supprimer les balises HTML.
		$text = wp_strip_all_tags( $text );

		// Convertir en minuscules.
		$text = strtolower( $text );

		// Remplacer les caractères spéciaux par des tirets.
		$text = preg_replace( '/[^a-z0-9]+/', '-', $text );

		// Supprimer les tirets en début et fin.
		$text = trim( $text, '-' );

		// Limiter la longueur.
		if ( strlen( $text ) > 50 ) {
			$text = substr( $text, 0, 50 );
			$text = rtrim( $text, '-' );
		}

		// Ajouter un préfixe pour éviter les conflits.
		$id = 'heading-' . $text;

		// S'assurer que l'ID est unique.
		static $used_ids = array();
		$original_id = $id;
		$counter     = 1;

		while ( isset( $used_ids[ $id ] ) ) {
			$id = $original_id . '-' . $counter;
			$counter++;
		}

		$used_ids[ $id ] = true;

		return $id;
	}
}
