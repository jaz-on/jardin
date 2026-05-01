<?php
/**
 * Title: Footer — 4 colonnes + webring
 * Slug: jardin-theme/footer-main
 * Categories: footer
 * Description: Four-column mockup (.cols), hub links from page templates where possible. See mockup.html ~13315.
 * Inserter: no
 *
 * @package Jardin_Theme */

defined( 'ABSPATH' ) || exit;

/**
 * @param string $path Path with leading slash (fallback when no template page).
 * @return string
 */
$u = static function ( string $path ): string {
	return esc_url( home_url( $path ) );
};

$activity_url   = function_exists( 'jardin_get_activity_archive_url' ) ? jardin_get_activity_archive_url() : $u( '/activites/' );
$activity_label = '/' . ( function_exists( 'jardin_get_activity_path_segment' ) ? jardin_get_activity_path_segment() : 'activites' );

$journal_url   = function_exists( 'jardin_journal_hub_url' ) ? jardin_journal_hub_url() : $u( '/journal/' );
$journal_label = function_exists( 'jardin_journal_hub_label' ) ? jardin_journal_hub_label() : '/journal';

$articles_url   = function_exists( 'jardin_articles_hub_url' ) ? jardin_articles_hub_url() : $u( '/articles/' );
$articles_label = function_exists( 'jardin_articles_hub_label' ) ? jardin_articles_hub_label() : '/articles';

$events_url   = function_exists( 'jardin_get_event_archive_url' ) ? jardin_get_event_archive_url() : '';
$events_label = function_exists( 'jardin_get_event_archive_label' ) ? jardin_get_event_archive_label() : '';
if ( '' === $events_url ) {
	$events_url = $u( '/evenements/' );
}
if ( '' === $events_label ) {
	$events_label = '/evenements';
}

$projects_url   = function_exists( 'jardin_projects_hub_url' ) ? jardin_projects_hub_url() : $u( '/projets/' );
$projects_label = function_exists( 'jardin_projects_hub_label' ) ? jardin_projects_hub_label() : '/projets';

$now_url   = function_exists( 'jardin_updates_hub_url' ) ? jardin_updates_hub_url() : $u( '/maintenant/' );
$now_label = function_exists( 'jardin_updates_hub_label' ) ? jardin_updates_hub_label() : '/maintenant';

$toasts_url   = function_exists( 'jardin_toasts_hub_url' ) ? jardin_toasts_hub_url() : $u( '/toast/' );
$toasts_label = function_exists( 'jardin_toasts_hub_label' ) ? jardin_toasts_hub_label() : '/toast';

$dlc_url   = function_exists( 'jardin_dlc_hub_url' ) ? jardin_dlc_hub_url() : $u( '/dlc/' );
$dlc_label = function_exists( 'jardin_dlc_hub_label' ) ? jardin_dlc_hub_label() : '/dlc';

$blogroll_url   = function_exists( 'jardin_blogroll_hub_url' ) ? jardin_blogroll_hub_url() : $u( '/blogroll/' );
$blogroll_label = function_exists( 'jardin_blogroll_hub_label' ) ? jardin_blogroll_hub_label() : '/blogroll';

?>
<!-- wp:html -->
<div class="cols">
	<div>
		<h4><?php esc_html_e( 'Explore', 'jardin-theme' ); ?></h4>
		<ul>
			<li><a href="<?php echo esc_url( $journal_url ); ?>"><?php echo esc_html( $journal_label ); ?></a></li>
			<li><a href="<?php echo esc_url( $articles_url ); ?>"><?php echo esc_html( $articles_label ); ?></a></li>
			<li><a href="<?php echo esc_url( $activity_url ); ?>"><?php echo esc_html( $activity_label ); ?></a></li>
			<li><a href="<?php echo esc_url( $events_url ); ?>"><?php echo esc_html( $events_label ); ?></a></li>
			<li><a href="<?php echo esc_url( $projects_url ); ?>"><?php echo esc_html( $projects_label ); ?></a></li>
		</ul>
	</div>
	<div>
		<h4><?php esc_html_e( 'More to explore', 'jardin-theme' ); ?></h4>
		<ul>
			<li><a href="<?php echo esc_url( $now_url ); ?>"><?php echo esc_html( $now_label ); ?></a></li>
			<li><a href="<?php echo esc_url( $dlc_url ); ?>"><?php echo esc_html( $dlc_label ); ?></a></li>
			<li><a href="<?php echo esc_url( $toasts_url ); ?>"><?php echo esc_html( $toasts_label ); ?></a></li>
			<li><a href="<?php echo esc_url( $blogroll_url ); ?>"><?php echo esc_html( $blogroll_label ); ?></a></li>
		</ul>
	</div>
	<div>
		<h4><?php esc_html_e( 'This site', 'jardin-theme' ); ?></h4>
		<ul>
			<li><a href="<?php echo $u( '/index/' ); ?>"><?php echo esc_html( '/index' ); ?></a></li>
			<li><a href="<?php echo $u( '/colophon/' ); ?>"><?php echo esc_html( '/colophon' ); ?></a></li>
			<li><a href="<?php echo $u( '/flux/' ); ?>"><?php echo esc_html( '/flux' ); ?></a></li>
			<li><a href="<?php echo $u( '/styleguide/' ); ?>"><?php echo esc_html( '/styleguide' ); ?></a></li>
			<li><a href="<?php echo $u( '/ia/' ); ?>"><?php echo esc_html( '/ia' ); ?></a></li>
			<li><a href="<?php echo $u( '/mentions-legales/' ); ?>"><?php echo esc_html( '/mentions-légales' ); ?></a></li>
		</ul>
	</div>
	<div>
		<h4><?php esc_html_e( 'Reach me', 'jardin-theme' ); ?></h4>
		<ul>
			<li><a href="<?php echo $u( '/contact/' ); ?>"><?php echo esc_html( '/contact' ); ?></a></li>
			<li><a href="<?php echo $u( '/social/' ); ?>"><?php echo esc_html( '/social' ); ?></a></li>
			<li><a href="https://bsky.app/profile/jasonrouet.com" rel="me noopener" target="_blank"><?php esc_html_e( 'Bluesky', 'jardin-theme' ); ?></a></li>
			<li><a href="https://www.linkedin.com/in/jasonrouet" rel="me noopener" target="_blank"><?php esc_html_e( 'LinkedIn', 'jardin-theme' ); ?></a></li>
			<li><a href="https://pouet.chapril.org/@jrouet" rel="me noopener" target="_blank"><?php esc_html_e( 'Mastodon', 'jardin-theme' ); ?></a></li>
		</ul>
	</div>
</div>

<div class="webring">
	<span class="webring-label">
		<a href="https://xn--sr8hvo.ws/" target="_blank" rel="noopener"><?php esc_html_e( 'IndieWeb webring', 'jardin-theme' ); ?> <span aria-hidden="true">🕸💍</span></a>
	</span>
	<span class="webring-nav">
		<a class="webring-prev" href="https://xn--sr8hvo.ws/previous"><?php esc_html_e( '← previous site', 'jardin-theme' ); ?></a>
		<span class="webring-sep" aria-hidden="true">·</span>
		<a class="webring-next" href="https://xn--sr8hvo.ws/next"><?php esc_html_e( 'next site →', 'jardin-theme' ); ?></a>
	</span>
</div>
<!-- /wp:html -->
