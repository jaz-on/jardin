<?php
/**
 * Hub « activités » (FR: /activites/, EN: /activities/) pour l’archive CPT IndieBlocks `iwcpt_note`.
 *
 * Segments URL sans accent (aligné /evenements/). Libellés UI via i18n (Activités / Activities).
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/**
 * Slug de chemin pour la langue Polylang (slug), ou estimation depuis la locale.
 *
 * @param string $lang_slug Ex. fr, en.
 * @return string activites | activities
 */
function jardin_get_activity_path_segment_for_lang( string $lang_slug ): string {
	$lang_slug = sanitize_key( $lang_slug );
	return ( 'fr' === $lang_slug ) ? 'activites' : 'activities';
}

/**
 * Slug de chemin pour la langue courante (front).
 *
 * @return string
 */
function jardin_get_activity_path_segment(): string {
	if ( function_exists( 'pll_current_language' ) ) {
		$slug = (string) pll_current_language( 'slug' );
		if ( '' !== $slug ) {
			return jardin_get_activity_path_segment_for_lang( $slug );
		}
	}
	$loc = get_locale();
	return ( str_starts_with( (string) $loc, 'fr' ) ) ? 'activites' : 'activities';
}

/**
 * URL absolue de l’archive « activités » (iwcpt_note) pour la langue courante.
 *
 * @return string
 */
function jardin_get_activity_archive_url(): string {
	$seg = jardin_get_activity_path_segment();

	if ( function_exists( 'pll_home_url' ) ) {
		$lang = function_exists( 'pll_current_language' ) ? (string) pll_current_language( 'slug' ) : '';
		if ( '' === $lang && function_exists( 'pll_default_language' ) ) {
			$lang = (string) pll_default_language( 'slug' );
		}
		if ( '' !== $lang ) {
			return trailingslashit( pll_home_url( $lang ) ) . $seg . '/';
		}
	}

	return trailingslashit( home_url( '/' . $seg ) );
}

/**
 * Libellé court pour filtres et pills (activités / activities), aligné sur le segment du hub.
 *
 * @return string
 */
function jardin_get_activity_nav_label(): string {
	return 'activites' === jardin_get_activity_path_segment() ? __( 'activités', 'jardin-theme' ) : __( 'activities', 'jardin-theme' );
}

/**
 * Enregistre les règles de réécriture pour /activites/ et /activities/ (et préfixes Polylang).
 *
 * @return void
 */
function jardin_register_activity_archive_rewrites(): void {
	if ( ! post_type_exists( 'iwcpt_note' ) ) {
		return;
	}

	if ( function_exists( 'pll_languages_list' ) && function_exists( 'pll_default_language' ) ) {
		$langs   = pll_languages_list( array( 'fields' => 'slug' ) );
		$default = (string) pll_default_language( 'slug' );
		$hide    = function_exists( 'pll_get_option' ) ? (bool) pll_get_option( 'hide_default' ) : false;

		if ( is_array( $langs ) ) {
			foreach ( $langs as $lang_slug ) {
				$lang_slug = sanitize_key( (string) $lang_slug );
				$seg       = jardin_get_activity_path_segment_for_lang( $lang_slug );
				if ( $hide && $lang_slug === $default ) {
					// Langue par défaut sans préfixe : pas de query var `lang` (Polylang l’infère).
					add_rewrite_rule( '^' . $seg . '/?$', 'index.php?post_type=iwcpt_note', 'top' );
				} else {
					add_rewrite_rule( '^' . $lang_slug . '/' . $seg . '/?$', 'index.php?lang=' . $lang_slug . '&post_type=iwcpt_note', 'top' );
				}
			}
		}
		return;
	}

	$seg = jardin_get_activity_path_segment();
	add_rewrite_rule( '^' . $seg . '/?$', 'index.php?post_type=iwcpt_note', 'top' );
}
add_action( 'init', 'jardin_register_activity_archive_rewrites', 25 );

/**
 * Permalink canonique de l’archive iwcpt_note → hub activités.
 *
 * @param string $link     Default link.
 * @param string $post_type Post type.
 * @return string
 */
function jardin_filter_iwcpt_note_archive_link( string $link, string $post_type ): string {
	if ( 'iwcpt_note' !== $post_type ) {
		return $link;
	}
	return jardin_get_activity_archive_url();
}
add_filter( 'post_type_archive_link', 'jardin_filter_iwcpt_note_archive_link', 20, 2 );

/**
 * Redirige l’ancienne archive /notes/ (slug IndieBlocks) vers le hub activités.
 *
 * @return void
 */
function jardin_redirect_legacy_notes_archive(): void {
	if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		return;
	}
	if ( ! is_post_type_archive( 'iwcpt_note' ) ) {
		return;
	}
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( (string) $_SERVER['REQUEST_URI'] ) : '';
	if ( false === strpos( $uri, '/notes' ) ) {
		return;
	}
	wp_safe_redirect( jardin_get_activity_archive_url(), 301 );
	exit;
}
add_action( 'template_redirect', 'jardin_redirect_legacy_notes_archive', 1 );
