<?php
/**
 * Hub page URLs from FSE template assignment + CPT archive helpers + event archive title.
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Journal hub page URL (template `page-journal`).
 *
 * @return string Trailing slash.
 */
function jardin_journal_hub_url(): string {
	return jardin_get_hub_url_from_template_or_legacy( 'page-journal', '/journal/' );
}

/**
 * Path label for journal hub nav.
 *
 * @return string e.g. /mon-journal
 */
function jardin_journal_hub_label(): string {
	$url = jardin_get_page_url_for_theme_template( 'page-journal' );
	if ( '' !== $url ) {
		$l = jardin_get_path_label_for_url( $url );
		return '' !== $l ? $l : '/';
	}
	return '/' . trim( (string) apply_filters( 'jardin_hub_legacy_path', '/journal/', 'page-journal' ), '/' );
}

/**
 * Projects hub page URL (template `page-projects`).
 *
 * @return string
 */
function jardin_projects_hub_url(): string {
	return jardin_get_hub_url_from_template_or_legacy( 'page-projects', '/projets/' );
}

/**
 * Short path label for projects hub nav.
 *
 * @return string
 */
function jardin_projects_hub_label(): string {
	$url = jardin_get_page_url_for_theme_template( 'page-projects' );
	if ( '' !== $url ) {
		$l = jardin_get_path_label_for_url( $url );
		return '' !== $l ? $l : '/';
	}
	return '/' . trim( (string) apply_filters( 'jardin_hub_legacy_path', '/projets/', 'page-projects' ), '/' );
}

/**
 * Updates hub page URL (template `page-now`).
 *
 * @return string
 */
function jardin_updates_hub_url(): string {
	return jardin_get_hub_url_from_template_or_legacy( 'page-now', '/maintenant/' );
}

/**
 * Short path label for updates hub nav.
 *
 * @return string
 */
function jardin_updates_hub_label(): string {
	$url = jardin_get_page_url_for_theme_template( 'page-now' );
	if ( '' !== $url ) {
		$l = jardin_get_path_label_for_url( $url );
		return '' !== $l ? $l : '/';
	}
	return '/' . trim( (string) apply_filters( 'jardin_hub_legacy_path', '/maintenant/', 'page-now' ), '/' );
}

/**
 * Toasts hub page URL (`page-toast`, then legacy `page-bieres`).
 *
 * @return string
 */
function jardin_toasts_hub_url(): string {
	foreach ( array( 'page-toast', 'page-bieres' ) as $tpl ) {
		$url = jardin_get_page_url_for_theme_template( $tpl );
		if ( '' !== $url ) {
			return trailingslashit( $url );
		}
	}
	return jardin_get_hub_url_from_template_or_legacy( 'page-toast', '/toast/' );
}

/**
 * Short path label for toasts hub nav.
 *
 * @return string
 */
function jardin_toasts_hub_label(): string {
	$post = jardin_get_page_post_for_theme_template( 'page-toast' );
	if ( ! $post instanceof WP_Post ) {
		$post = jardin_get_page_post_for_theme_template( 'page-bieres' );
	}
	if ( $post instanceof WP_Post ) {
		$url = get_permalink( $post );
		if ( is_string( $url ) && '' !== $url ) {
			$l = jardin_get_path_label_for_url( $url );
			return '' !== $l ? $l : '/';
		}
	}
	return '/' . trim( (string) apply_filters( 'jardin_hub_legacy_path', '/toast/', 'page-toast' ), '/' );
}

/**
 * Articles hub (posts-only view page).
 *
 * @return string
 */
function jardin_articles_hub_url(): string {
	return jardin_get_hub_url_from_template_or_legacy( 'page-articles', '/articles/' );
}

/**
 * @return string
 */
function jardin_articles_hub_label(): string {
	$url = jardin_get_page_url_for_theme_template( 'page-articles' );
	if ( '' !== $url ) {
		$l = jardin_get_path_label_for_url( $url );
		return '' !== $l ? $l : '/';
	}
	return '/' . trim( (string) apply_filters( 'jardin_hub_legacy_path', '/articles/', 'page-articles' ), '/' );
}

/**
 * Event CPT archive URL (plugin rewrite), not a page template.
 *
 * @return string Empty if CPT missing.
 */
function jardin_get_event_archive_url(): string {
	$pt = 'event';
	if ( function_exists( 'jardin_events_get_post_type' ) ) {
		$candidate = (string) jardin_events_get_post_type();
		if ( '' !== $candidate && post_type_exists( $candidate ) ) {
			$pt = $candidate;
		}
	}
	if ( ! post_type_exists( $pt ) ) {
		return '';
	}
	$link = get_post_type_archive_link( $pt );
	return is_string( $link ) && '' !== $link ? trailingslashit( $link ) : '';
}

/**
 * Path label for events archive link.
 *
 * @return string
 */
function jardin_get_event_archive_label(): string {
	$url = jardin_get_event_archive_url();
	if ( '' === $url ) {
		return '';
	}
	$l = jardin_get_path_label_for_url( $url );
	return '' !== $l ? $l : '/';
}

/**
 * Support / “soutenir” page URL (`page-support` template).
 *
 * @return string
 */
function jardin_support_hub_url(): string {
	return jardin_get_hub_url_from_template_or_legacy( 'page-support', '/soutenir/' );
}

/**
 * About page URL (`page-about` template).
 *
 * @return string
 */
function jardin_about_hub_url(): string {
	return jardin_get_hub_url_from_template_or_legacy( 'page-about', '/a-propos/' );
}

/**
 * @return string
 */
function jardin_about_hub_label(): string {
	$url = jardin_get_page_url_for_theme_template( 'page-about' );
	if ( '' !== $url ) {
		$l = jardin_get_path_label_for_url( $url );
		return '' !== $l ? $l : '/';
	}
	return '/' . trim( (string) apply_filters( 'jardin_hub_legacy_path', '/a-propos/', 'page-about' ), '/' );
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
 * Page used to override event archive title (template `page-events-hub`, else legacy slug).
 *
 * @return \WP_Post|null
 */
function jardin_event_archive_title_hub_page(): ?WP_Post {
	$post = jardin_get_page_post_for_theme_template( 'page-events-hub' );
	if ( $post instanceof WP_Post ) {
		return $post;
	}
	// Ordered legacy page slugs when no page-events-hub template is assigned (first match wins); filter: jardin_event_archive_title_legacy_slugs.
	$slugs = apply_filters(
		'jardin_event_archive_title_legacy_slugs',
		array( 'evenements', 'events' )
	);
	foreach ( array_map( 'strval', (array) $slugs ) as $slug ) {
		$slug = trim( $slug );
		if ( '' === $slug ) {
			continue;
		}
		$page = jardin_get_hub_page_for_display( $slug );
		if ( $page instanceof WP_Post ) {
			return $page;
		}
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
	$pt = function_exists( 'jardin_events_get_post_type' ) ? jardin_events_get_post_type() : 'event';
	if ( ! is_post_type_archive( $pt ) ) {
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
	$pt = function_exists( 'jardin_events_get_post_type' ) ? jardin_events_get_post_type() : 'event';
	if ( ! is_post_type_archive( $pt ) ) {
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

/**
 * DLC hub page URL.
 *
 * @return string
 */
function jardin_dlc_hub_url(): string {
	return jardin_get_hub_url_from_template_or_legacy( 'page-dlc', '/dlc/' );
}

/**
 * @return string
 */
function jardin_dlc_hub_label(): string {
	$url = jardin_get_page_url_for_theme_template( 'page-dlc' );
	if ( '' !== $url ) {
		$l = jardin_get_path_label_for_url( $url );
		return '' !== $l ? $l : '/';
	}
	return '/' . trim( (string) apply_filters( 'jardin_hub_legacy_path', '/dlc/', 'page-dlc' ), '/' );
}

/**
 * Blogroll hub page URL.
 *
 * @return string
 */
function jardin_blogroll_hub_url(): string {
	return jardin_get_hub_url_from_template_or_legacy( 'page-blogroll', '/blogroll/' );
}

/**
 * @return string
 */
function jardin_blogroll_hub_label(): string {
	$url = jardin_get_page_url_for_theme_template( 'page-blogroll' );
	if ( '' !== $url ) {
		$l = jardin_get_path_label_for_url( $url );
		return '' !== $l ? $l : '/';
	}
	return '/' . trim( (string) apply_filters( 'jardin_hub_legacy_path', '/blogroll/', 'page-blogroll' ), '/' );
}
