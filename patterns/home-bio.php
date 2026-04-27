<?php
/**
 * Title: Home — Bio
 * Slug: jardin/home-bio
 * Categories: text
 * Description: Introductory bio paragraph for the home page.
 * Inserter: no
 *
 * @package Jardin
 */

?>
<!-- wp:paragraph {"className":"bio"} -->
<p class="bio"><?php
	echo wp_kses(
		sprintf(
			/* translators: 1: link to Mediapapa 2: link to WordPress Francophone */
			__( 'Jason Rouet, product manager WordPress chez <a href="%1$s" rel="noopener">Mediapapa</a>, président de l\'asso <a href="%2$s" rel="noopener">WordPress Francophone</a>, contributeur open&#8209;web depuis un bon bout de temps. Ici je bidouille, je note, je raconte.', 'jardin' ),
			'https://www.mediapapa.fr',
			'https://wpfr.net'
		),
		array(
			'a' => array(
				'href'   => true,
				'rel'    => true,
				'target' => true,
				'class'  => true,
			),
		)
	);
?></p>
<!-- /wp:paragraph -->
