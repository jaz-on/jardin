<?php
/**
 * Template de rendu pour le bloc Table of Contents.
 *
 * @package Jardin
 * @since 0.1.0
 *
 * @var array    $attributes Attributs du bloc.
 * @var string   $content Contenu du bloc.
 * @var WP_Block $block Instance du bloc.
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Récupérer et valider les attributs.
$heading_levels = isset( $attributes['headingLevels'] ) && is_array( $attributes['headingLevels'] ) ? $attributes['headingLevels'] : array( 2, 3, 4, 5, 6 );

// Ne garder que les niveaux valides entre 2 et 6.
$heading_levels = array_values(
	array_filter(
		$heading_levels,
		static function ( $level ) {
			$level = (int) $level;
			return $level >= 2 && $level <= 6;
		}
	)
);

if ( empty( $heading_levels ) ) {
	$heading_levels = array( 2, 3, 4, 5, 6 );
}

$show_title = isset( $attributes['showTitle'] ) ? (bool) $attributes['showTitle'] : true;

// Le texte est échappé au moment de l'affichage.
$custom_title = isset( $attributes['customTitle'] ) && is_string( $attributes['customTitle'] )
	? $attributes['customTitle']
	: __( 'Table of Contents', 'jardin' );

$list_style = isset( $attributes['listStyle'] ) && in_array( $attributes['listStyle'], array( 'ul', 'ol' ), true )
	? $attributes['listStyle']
	: 'ul';

global $post;

if ( ! $post ) {
	return;
}

$toc = jardin_toc_extract_from_html( $post->post_content, $heading_levels );

if ( empty( $toc ) ) {
	return;
}

// Classes du wrapper.
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'jardin-toc',
	)
);
?>

<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( $show_title ) : ?>
		<h2 class="jardin-toc__title"><?php echo esc_html( $custom_title ); ?></h2>
	<?php endif; ?>

	<?php if ( 'ul' === $list_style ) : ?>
		<ul class="jardin-toc__list">
			<?php
			echo wp_kses(
				jardin_render_toc_list( $toc, 'ul' ),
				array(
					'ul' => array(
						'class' => true,
					),
					'ol' => array(
						'class' => true,
					),
					'li' => array(
						'class' => true,
					),
					'a'  => array(
						'href'  => true,
						'class' => true,
					),
				)
			);
			?>
		</ul>
	<?php else : ?>
		<ol class="jardin-toc__list">
			<?php
			echo wp_kses(
				jardin_render_toc_list( $toc, 'ol' ),
				array(
					'ul' => array(
						'class' => true,
					),
					'ol' => array(
						'class' => true,
					),
					'li' => array(
						'class' => true,
					),
					'a'  => array(
						'href'  => true,
						'class' => true,
					),
				)
			);
			?>
		</ol>
	<?php endif; ?>
</div>
