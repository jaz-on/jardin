<?php
/**
 * Class Jardin_Blocks
 *
 * Enregistrement automatique des blocs personnalisés.
 *
 * @package Jardin
 * @since 0.1.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe pour l'enregistrement automatique des blocs.
 */
class Jardin_Blocks {

	/**
	 * Chemin vers le dossier des blocs.
	 *
	 * @var string
	 */
	private $blocks_dir;

	/**
	 * Constructeur.
	 */
	public function __construct() {
		$this->blocks_dir = get_template_directory() . '/inc/blocks';
		add_action( 'init', array( $this, 'register_blocks' ) );
	}

	/**
	 * Enregistre tous les blocs trouvés dans inc/blocks/.
	 */
	public function register_blocks() {
		if ( ! file_exists( $this->blocks_dir ) ) {
			return;
		}

		$blocks = $this->scan_blocks_directory( $this->blocks_dir );

		foreach ( $blocks as $block_path ) {
			$this->register_block( $block_path );
		}
	}

	/**
	 * Scanne récursivement le dossier des blocs pour trouver tous les block.json.
	 *
	 * @param string $dir Dossier à scanner.
	 * @return array Liste des chemins vers les block.json.
	 */
	private function scan_blocks_directory( $dir ) {
		$blocks = array();

		if ( ! is_dir( $dir ) ) {
			return $blocks;
		}

		$items = scandir( $dir );

		foreach ( $items as $item ) {
			if ( $item === '.' || $item === '..' ) {
				continue;
			}

			$item_path = $dir . '/' . $item;

			if ( is_dir( $item_path ) ) {
				$block_json = $item_path . '/block.json';
				if ( file_exists( $block_json ) ) {
					$blocks[] = $block_json;
				}
			}
		}

		return $blocks;
	}

	/**
	 * Enregistre un bloc à partir de son block.json.
	 *
	 * @param string $block_json_path Chemin vers le block.json.
	 */
	private function register_block( $block_json_path ) {
		if ( ! file_exists( $block_json_path ) ) {
			return;
		}

		$block_dir = dirname( $block_json_path );
		register_block_type( $block_dir );
	}
}
