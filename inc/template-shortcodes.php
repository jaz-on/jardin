<?php
/**
 * Shortcodes for translatable copy inside FSE HTML templates.
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Intro paragraph for the journal hub page template.
 *
 * @return string
 */
function jardin_shortcode_journal_intro(): string {
	$text = __( 'A mixed timeline of long posts, notes, likes, bookmarks, listens, check-ins, and events—newest first. Use the filters to narrow by kind.', 'jardin' );
	return '<p class="has-text-muted-color has-text-color has-sm-font-size">' . esc_html( $text ) . '</p>';
}
add_shortcode( 'jardin_journal_intro', 'jardin_shortcode_journal_intro' );

/**
 * Relative filter links for the journal hub (?kind=).
 *
 * @return string
 */
function jardin_shortcode_journal_filters(): string {
	$label = esc_attr__( 'Journal filters', 'jardin' );
	$links = array(
		array( 'href' => '?', 'label' => __( 'All', 'jardin' ) ),
		array( 'href' => '?kind=note', 'label' => __( 'Notes', 'jardin' ) ),
		array( 'href' => '?kind=like', 'label' => __( 'Likes', 'jardin' ) ),
		array( 'href' => '?kind=bookmark', 'label' => __( 'Bookmarks', 'jardin' ) ),
		array( 'href' => '?kind=quote', 'label' => __( 'Quotes', 'jardin' ) ),
		array( 'href' => '?kind=til', 'label' => __( 'TIL', 'jardin' ) ),
		array( 'href' => '?kind=jam', 'label' => __( 'Jams', 'jardin' ) ),
		array( 'href' => '?kind=review', 'label' => __( 'Reviews', 'jardin' ) ),
		array( 'href' => '?kind=event', 'label' => __( 'Events', 'jardin' ) ),
	);

	$parts = array();
	foreach ( $links as $i => $item ) {
		if ( $i > 0 ) {
			$parts[] = '<span class="jardin-journal-filters__sep" aria-hidden="true"> · </span>';
		}
		$parts[] = sprintf(
			'<a class="jardin-journal-filters__link" href="%1$s">%2$s</a>',
			esc_url( $item['href'] ),
			esc_html( $item['label'] )
		);
	}

	$inner = implode( '', $parts );

	return sprintf(
		'<nav class="jardin-journal-filters" aria-label="%1$s"><p class="jardin-journal-filters__inner has-text-muted-color has-sm-font-size">%2$s</p></nav>',
		$label,
		$inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — built from esc_url/esc_html above.
	);
}
add_shortcode( 'jardin_journal_filters', 'jardin_shortcode_journal_filters' );

/**
 * Empty state for journal query loop.
 *
 * @return string
 */
function jardin_shortcode_journal_empty(): string {
	$text = __( 'No journal items yet.', 'jardin' );
	return '<p>' . esc_html( $text ) . '</p>';
}
add_shortcode( 'jardin_journal_empty', 'jardin_shortcode_journal_empty' );

/**
 * Empty state for home query loop.
 *
 * @return string
 */
function jardin_shortcode_home_empty(): string {
	$text = __( 'No posts yet.', 'jardin' );
	return '<p>' . esc_html( $text ) . '</p>';
}
add_shortcode( 'jardin_home_empty', 'jardin_shortcode_home_empty' );

/**
 * Single template footer zone title.
 *
 * @return string
 */
function jardin_shortcode_single_footer_title(): string {
	return '<h2 class="wp-block-heading has-lg-font-size">' . esc_html__( 'Reactions & discussion', 'jardin' ) . '</h2>';
}
add_shortcode( 'jardin_single_footer_title', 'jardin_shortcode_single_footer_title' );

/**
 * Single template footer zone description.
 *
 * @return string
 */
function jardin_shortcode_single_footer_lede(): string {
	$text = __( 'Comments and reactions from readers appear below when enabled for this content type.', 'jardin' );
	return '<p class="has-text-muted-color has-text-color has-sm-font-size">' . esc_html( $text ) . '</p>';
}
add_shortcode( 'jardin_single_footer_lede', 'jardin_shortcode_single_footer_lede' );

/**
 * Footer column heading — site / branding column.
 *
 * @return string
 */
function jardin_shortcode_footer_site_heading(): string {
	return '<h3 class="wp-block-heading has-sm-font-size">' . esc_html__( 'Site', 'jardin' ) . '</h3>';
}
add_shortcode( 'jardin_footer_site_heading', 'jardin_shortcode_footer_site_heading' );

/**
 * Footer column heading — secondary navigation.
 *
 * @return string
 */
function jardin_shortcode_footer_explore_heading(): string {
	return '<h3 class="wp-block-heading has-sm-font-size">' . esc_html__( 'Explore', 'jardin' ) . '</h3>';
}
add_shortcode( 'jardin_footer_explore_heading', 'jardin_shortcode_footer_explore_heading' );

/**
 * Copyright line (site title + year).
 *
 * @return string
 */
function jardin_shortcode_footer_copyright(): string {
	$year = (int) gmdate( 'Y' );
	/* translators: 1: four-digit year, 2: site name */
	$text = sprintf( __( '© %1$s %2$s', 'jardin' ), (string) $year, get_bloginfo( 'name' ) );
	return '<p class="has-text-align-center has-text-muted-color has-text-color has-sm-font-size">' . esc_html( $text ) . '</p>';
}
add_shortcode( 'jardin_footer_copyright', 'jardin_shortcode_footer_copyright' );

/**
 * Compact search form for the header (block templates cannot call PHP/i18n directly).
 *
 * @return string
 */
function jardin_shortcode_header_search(): string {
	$label = esc_attr__( 'Search', 'jardin' );
	$form  = get_search_form(
		array(
			'echo'       => false,
			'aria_label' => $label,
		)
	);
	return '<div class="jardin-header-search">' . $form . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — core search form markup.
}
add_shortcode( 'jardin_header_search', 'jardin_shortcode_header_search' );

/**
 * Generic placeholder paragraph for pages waiting on plugin content (Phase 3+).
 *
 * @param array<string,string> $atts Shortcode attributes. `type` one of now, beers, blogroll, generic.
 * @return string
 */
function jardin_shortcode_placeholder( $atts ): string {
	$atts = shortcode_atts(
		array(
			'type' => 'generic',
		),
		$atts,
		'jardin_placeholder'
	);

	switch ( $atts['type'] ) {
		case 'now':
			$message = __( 'Live “now” summary blocks (for example Last.fm) will load here once the listening plugin is connected. Page body below remains free for editorial content.', 'jardin' );
			break;
		case 'beers':
			$message = __( 'Recent check-ins and beer grids from beer-journal will appear here in a later phase. Use the page content for any introductory copy.', 'jardin' );
			break;
		case 'blogroll':
			$message = __( 'The blogroll grid powered by feed-journal will appear here in a later phase. Use the page content for notes in the meantime.', 'jardin' );
			break;
		default:
			$message = __( 'Content for this section will appear here once the related features are connected.', 'jardin' );
			break;
	}

	return '<p class="has-text-muted-color has-text-color">' . esc_html( $message ) . '</p>';
}
add_shortcode( 'jardin_placeholder', 'jardin_shortcode_placeholder' );

/**
 * 404 template: title, message, and search form (translatable).
 *
 * @return string
 */
function jardin_shortcode_404_content(): string {
	$h1 = '<h1 class="wp-block-heading">' . esc_html__( 'Page not found', 'jardin' ) . '</h1>';
	$p   = '<p>' . esc_html__( 'The page you are looking for does not exist or has moved.', 'jardin' ) . '</p>';
	$form = get_search_form(
		array(
			'echo'       => false,
			'aria_label' => esc_attr__( 'Search', 'jardin' ),
		)
	);
	return $h1 . $p . '<div class="jardin-404-search">' . $form . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_shortcode( 'jardin_404_content', 'jardin_shortcode_404_content' );

/**
 * Search results empty state.
 *
 * @return string
 */
function jardin_shortcode_search_empty(): string {
	return '<p>' . esc_html__( 'No results found.', 'jardin' ) . '</p>';
}
add_shortcode( 'jardin_search_empty', 'jardin_shortcode_search_empty' );
