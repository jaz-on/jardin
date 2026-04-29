<?php
/**
 * DLC page hardening: force the dedicated template and canonical block content.
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Resolve the DLC page id once and cache it.
 *
 * @return int
 */
function jardin_get_dlc_page_id(): int {
	static $dlc_id = null;
	if ( null !== $dlc_id ) {
		return $dlc_id;
	}

	$page = get_page_by_path( 'dlc' );
	$dlc_id = ( $page instanceof WP_Post && 'page' === $page->post_type ) ? (int) $page->ID : 0;
	return $dlc_id;
}

/**
 * Check whether current front request targets the DLC page.
 *
 * @return bool
 */
function jardin_is_dlc_request(): bool {
	return is_page() && get_queried_object_id() === jardin_get_dlc_page_id() && 0 !== jardin_get_dlc_page_id();
}

/**
 * Force `_wp_page_template` to `page-dlc` for the DLC page.
 *
 * This guarantees the filesystem template is used even if a stale DB template
 * assignment exists on the page object.
 *
 * @param mixed        $value    Existing metadata value.
 * @param int          $object_id Post ID.
 * @param string       $meta_key Metadata key.
 * @param bool         $single   Whether single value is requested.
 * @param string       $meta_type Object meta type.
 * @return mixed
 */
function jardin_force_dlc_page_template( $value, $object_id, $meta_key, $single, $meta_type ) {
	if ( 'post' !== $meta_type || '_wp_page_template' !== $meta_key || (int) $object_id !== jardin_get_dlc_page_id() ) {
		return $value;
	}

	return $single ? 'page-dlc' : array( 'page-dlc' );
}
add_filter( 'get_post_metadata', 'jardin_force_dlc_page_template', 10, 5 );

/**
 * Canonical dynamic block content for the DLC page.
 *
 * @return string
 */
function jardin_get_dlc_dynamic_blocks_markup(): string {
	return implode(
		"\n\n",
		array(
			'<!-- wp:paragraph {"textColor":"text-muted"} -->',
			'<p class="has-text-muted-color has-text-color">Mon historique d\'écoute, alimenté par <a href="https://last.fm" target="_blank" rel="noopener">Last.fm</a> via un plugin maison. Scrobblé depuis 2006.</p>',
			'<!-- /wp:paragraph -->',
			'<!-- wp:jardin/lastfm-stats /-->',
			'<!-- wp:jardin/lastfm-now-playing /-->',
			'<!-- wp:jardin/recent-jams {"layout":"cards","postsToShow":4} /-->',
			'<!-- wp:jardin/lastfm-rankings {"showTabs":true,"defaultPeriod":"7day","artistsLimit":10,"albumsLimit":5} /-->',
			'<!-- wp:jardin/recent-listens {"layout":"scrobbles","postsToShow":15} /-->',
			'<!-- wp:jardin/lastfm-rankings {"showTabs":false,"defaultPeriod":"overall","artistsLimit":10,"showAlbums":false} /-->',
		)
	);
}

/**
 * Replace page content with dynamic DLC blocks on /dlc/.
 *
 * @param string $content Rendered post content.
 * @return string
 */
function jardin_force_dlc_dynamic_content( string $content ): string {
	if ( ! jardin_is_dlc_request() ) {
		return $content;
	}

	return do_blocks( jardin_get_dlc_dynamic_blocks_markup() );
}
add_filter( 'the_content', 'jardin_force_dlc_dynamic_content', 100 );
