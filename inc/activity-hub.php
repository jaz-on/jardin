<?php
/**
 * Activities hub (FR: /activites/, EN: /activities/) for the IndieBlocks `iwcpt_note` archive CPT.
 *
 * URL segments are ASCII (aligned with /evenements/). UI labels use gettext (e.g. Activities).
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/**
 * Path segment for a Polylang language slug, or inferred from locale.
 *
 * @param string $lang_slug Ex. fr, en.
 * @return string activites | activities
 */
function jardin_get_activity_path_segment_for_lang( string $lang_slug ): string {
	$lang_slug = sanitize_key( $lang_slug );
	return ( 'fr' === $lang_slug ) ? 'activites' : 'activities';
}

/**
 * Path segment for the current front-end language.
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
 * Absolute URL of the `iwcpt_note` archive hub for the current language.
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
 * Short label for filters and pills (translate via fr_FR.po on French locales).
 *
 * @return string
 */
function jardin_get_activity_nav_label(): string {
	return __( 'Activities', 'jardin-theme' );
}

/**
 * Register rewrite rules for /activites/ and /activities/ (including Polylang prefixes).
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
					// Default language without prefix: no `lang` query var (Polylang infers it).
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
 * Canonical permalink for the `iwcpt_note` archive → activities hub.
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
 * Redirect legacy /notes/ IndieBlocks archive slug to the activities hub.
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
