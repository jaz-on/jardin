<?php
/**
 * Title: Footer — 4 colonnes + webring
 * Slug: jardin/footer-main
 * Categories: footer
 * Description: Grille mockup (.cols), liens avec home_url, webring IndieWeb. Voir mockup.html ~13315.
 * Inserter: no
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * @param string $path Path with leading slash.
 * @return string
 */
$u = static function ( string $path ): string {
	return esc_url( home_url( $path ) );
};

$activity_url   = function_exists( 'jardin_get_activity_archive_url' ) ? jardin_get_activity_archive_url() : $u( '/activite/' );
$activity_label = '/' . ( function_exists( 'jardin_get_activity_path_segment' ) ? jardin_get_activity_path_segment() : 'activite' );

?>
<!-- wp:html -->
<div class="cols">
	<div>
		<h4><?php esc_html_e( 'Explorer', 'jardin' ); ?></h4>
		<ul>
			<li><a href="<?php echo $u( '/journal/' ); ?>"><?php echo esc_html( '/journal' ); ?></a></li>
			<li><a href="<?php echo $u( '/articles/' ); ?>"><?php echo esc_html( '/articles' ); ?></a></li>
			<li><a href="<?php echo esc_url( $activity_url ); ?>"><?php echo esc_html( $activity_label ); ?></a></li>
			<li><a href="<?php echo $u( '/evenements/' ); ?>"><?php echo esc_html( '/evenements' ); ?></a></li>
			<li><a href="<?php echo $u( '/projets/' ); ?>"><?php echo esc_html( '/projets' ); ?></a></li>
		</ul>
	</div>
	<div>
		<h4><?php esc_html_e( 'Explorer (suite)', 'jardin' ); ?></h4>
		<ul>
			<li><a href="<?php echo $u( '/maintenant/' ); ?>"><?php echo esc_html( '/maintenant' ); ?></a></li>
			<li><a href="<?php echo $u( '/dlc/' ); ?>"><?php echo esc_html( '/dlc' ); ?></a></li>
			<li><a href="<?php echo $u( '/bieres/' ); ?>"><?php echo esc_html( '/bieres' ); ?></a></li>
			<li><a href="<?php echo $u( '/blogroll/' ); ?>"><?php echo esc_html( '/blogroll' ); ?></a></li>
		</ul>
	</div>
	<div>
		<h4><?php esc_html_e( 'Le site', 'jardin' ); ?></h4>
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
		<h4><?php esc_html_e( 'Me joindre', 'jardin' ); ?></h4>
		<ul>
			<li><a href="<?php echo $u( '/contact/' ); ?>"><?php echo esc_html( '/contact' ); ?></a></li>
			<li><a href="<?php echo $u( '/social/' ); ?>"><?php echo esc_html( '/social' ); ?></a></li>
			<li><a href="https://bsky.app/profile/jasonrouet.com" rel="me noopener" target="_blank"><?php esc_html_e( 'Bluesky', 'jardin' ); ?></a></li>
			<li><a href="https://www.linkedin.com/in/jasonrouet" rel="me noopener" target="_blank"><?php esc_html_e( 'LinkedIn', 'jardin' ); ?></a></li>
			<li><a href="https://pouet.chapril.org/@jrouet" rel="me noopener" target="_blank"><?php esc_html_e( 'Mastodon', 'jardin' ); ?></a></li>
		</ul>
	</div>
</div>

<div class="webring">
	<span class="webring-label">
		<a href="https://xn--sr8hvo.ws/" target="_blank" rel="noopener"><?php esc_html_e( 'IndieWeb webring', 'jardin' ); ?> <span aria-hidden="true">🕸💍</span></a>
	</span>
	<span class="webring-nav">
		<a class="webring-prev" href="https://xn--sr8hvo.ws/previous"><?php esc_html_e( '← site précédent', 'jardin' ); ?></a>
		<span class="webring-sep" aria-hidden="true">·</span>
		<a class="webring-next" href="https://xn--sr8hvo.ws/next"><?php esc_html_e( 'site suivant →', 'jardin' ); ?></a>
	</span>
</div>
<!-- /wp:html -->
