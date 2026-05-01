<?php
/**
 * Hub page URLs (FR paths at site root vs EN via Polylang) and archive title overrides.
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hub URL from FR and EN path segments (last segments only, no leading slashes).
 *
 * @param string $fr_slug FR default path segment (e.g. projets).
 * @param string $en_slug EN path segment (e.g. projects).
 * @return string Permalink with trailing slash.
 */
function jardin_hub_url_for_slug_pair( string $fr_slug, string $en_slug ): string {
	$fr_slug = trim( $fr_slug, '/' );
	$en_slug = trim( $en_slug, '/' );
	if ( function_exists( 'pll_current_language' ) && function_exists( 'pll_home_url' ) ) {
		$lang = (string) pll_current_language( 'slug' );
		if ( 'en' === $lang ) {
			return trailingslashit( untrailingslashit( pll_home_url( $lang ) ) . '/' . $en_slug );
		}
	}
	return trailingslashit( home_url( '/' . $fr_slug . '/' ) );
}

/**
 * Projects hub page URL (matches CPT rewrite slug).
 *
 * @return string
 */
function jardin_projects_hub_url(): string {
	return jardin_hub_url_for_slug_pair( 'projets', 'projects' );
}

/**
 * Short path label for nav (leading slash, no trailing slash).
 *
 * @return string e.g. /projets or /projects
 */
function jardin_projects_hub_label(): string {
	if ( function_exists( 'pll_current_language' ) && 'en' === pll_current_language( 'slug' ) ) {
		return '/projects';
	}
	return '/projets';
}

/**
 * Now hub page URL (WordPress page; CPT singles may live under the same prefix).
 *
 * @return string
 */
function jardin_now_hub_url(): string {
	return jardin_hub_url_for_slug_pair( 'maintenant', 'now' );
}

/**
 * Short path label for now hub nav.
 *
 * @return string
 */
function jardin_now_hub_label(): string {
	if ( function_exists( 'pll_current_language' ) && 'en' === pll_current_language( 'slug' ) ) {
		return '/now';
	}
	return '/maintenant';
}

/**
 * Toasts hub page URL (Untappd stats page; FR slug differs from EN).
 *
 * @return string
 */
function jardin_toasts_hub_url(): string {
	return jardin_hub_url_for_slug_pair( 'toast', 'toasts' );
}

/**
 * Short path label for toasts hub nav.
 *
 * @return string e.g. /toast or /toasts
 */
function jardin_toasts_hub_label(): string {
	if ( function_exists( 'pll_current_language' ) && 'en' === pll_current_language( 'slug' ) ) {
		return '/toasts';
	}
	return '/toast';
}

/**
 * Find a published page by path slug, respecting Polylang current language when available.
 *
 * @param string $slug Page slug (non-hierarchical).
 * @return \WP_Post|null
 */
function jardin_get_hub_page_for_display( string $slug ): ?WP_Post {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	if ( ! $page instanceof WP_Post || 'publish' !== $page->post_status ) {
		return null;
	}
	if ( function_exists( 'pll_get_post_language' ) && function_exists( 'pll_current_language' ) && function_exists( 'pll_get_post' ) ) {
		$current = (string) pll_current_language( 'slug' );
		$have    = (string) pll_get_post_language( $page->ID, 'slug' );
		if ( '' !== $current && '' !== $have && $current !== $have ) {
			$translated_id = (int) pll_get_post( $page->ID, $current );
			$translated    = $translated_id > 0 ? get_post( $translated_id ) : null;
			if ( $translated instanceof WP_Post && 'publish' === $translated->post_status ) {
				return $translated;
			}
			return null;
		}
	}
	return $page;
}

/**
 * Hub page whose title should replace the event CPT archive heading when URLs collide with that page slug.
 *
 * @return \WP_Post|null
 */
function jardin_event_archive_title_hub_page(): ?WP_Post {
	$slug = 'evenements';
	if ( function_exists( 'pll_current_language' ) && 'en' === pll_current_language( 'slug' ) ) {
		$slug = 'events';
	}
	$page = jardin_get_hub_page_for_display( $slug );
	if ( $page instanceof WP_Post ) {
		return $page;
	}
	if ( 'events' === $slug ) {
		return jardin_get_hub_page_for_display( 'evenements' );
	}
	return null;
}

/**
 * Prefer the editor-defined hub page title on event archives (avoids English CPT label).
 *
 * @param mixed  $title           Archive title from core.
 * @param string $original_title Unused.
 * @param string $prefix         Unused.
 * @return mixed
 */
function jardin_filter_event_archive_title_use_hub_page( $title, $original_title = '', $prefix = '' ) {
	unset( $original_title, $prefix );
	if ( ! is_post_type_archive( 'event' ) ) {
		return $title;
	}
	$page = jardin_event_archive_title_hub_page();
	if ( ! $page instanceof WP_Post ) {
		return $title;
	}
	return get_the_title( $page );
}

/**
 * @param array<string, string> $parts Title parts.
 * @return array<string, string>
 */
function jardin_filter_event_archive_document_title( array $parts ): array {
	if ( ! is_post_type_archive( 'event' ) ) {
		return $parts;
	}
	$page = jardin_event_archive_title_hub_page();
	if ( ! $page instanceof WP_Post ) {
		return $parts;
	}
	$parts['title'] = wp_strip_all_tags( get_the_title( $page ) );
	return $parts;
}

add_filter( 'get_the_archive_title', 'jardin_filter_event_archive_title_use_hub_page', 10, 3 );
add_filter( 'document_title_parts', 'jardin_filter_event_archive_document_title', 20 );
