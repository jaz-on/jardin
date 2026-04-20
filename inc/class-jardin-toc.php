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
	 * Registre des ids d’ancre pour le passage courant sur the_content.
	 *
	 * @var array<string, bool>
	 */
	private $toc_id_registry = array();

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

		$this->toc_id_registry = array();

		$pattern = '/<h([2-6])([^>]*)>(.*?)<\/h[2-6]>/is';

		return preg_replace_callback(
			$pattern,
			array( $this, 'add_id_to_heading' ),
			$content
		);
	}

	/**
	 * Callback pour ajouter un ID à un titre.
	 *
	 * @param array $matches Matches du preg_replace_callback.
	 * @return string Titre avec ID ajouté.
	 */
	private function add_id_to_heading( $matches ) {
		$level = $matches[1];
		$attrs = $matches[2];
		$inner = $matches[3];

		if ( preg_match( '/\bid\s*=\s*["\']([^"\']+)["\']/', $attrs, $id_match ) ) {
			$this->toc_id_registry[ $id_match[1] ] = true;
			return $matches[0];
		}

		$id_value = jardin_toc_unique_heading_id( $inner, $this->toc_id_registry );
		$id_attr  = ' id="' . esc_attr( $id_value ) . '"';
		$new_tag  = '<h' . $level . $attrs . $id_attr . '>' . $inner . '</h' . $level . '>';

		return $new_tag;
	}
}
