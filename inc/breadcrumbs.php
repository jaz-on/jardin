<?php
/**
 * Ajustements du fil d’Ariane Yoast (bloc `yoast-seo/breadcrumbs` dans les modèles HTML).
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Yoast affiche parfois « Bières » sur le hub Toasts ; on aligne le dernier maillon sur le libellé produit.
 *
 * @param array<int, array<string, mixed>>|mixed $links Maillons du fil.
 * @return array<int, array<string, mixed>>|mixed
 */
function jardin_filter_yoast_breadcrumb_toasts_hub( $links ) {
	if ( ! is_array( $links ) || ! is_page() ) {
		return $links;
	}
	$slug = (string) get_post_field( 'post_name', (int) get_queried_object_id(), 'raw' );
	if ( ! in_array( $slug, array( 'toast', 'bieres', 'toasts', 'beers' ), true ) ) {
		return $links;
	}
	$label = __( 'Toasts', 'jardin-theme' );
	$last  = count( $links ) - 1;
	if ( $last < 0 ) {
		return $links;
	}
	if ( isset( $links[ $last ]['text'] ) ) {
		$links[ $last ]['text'] = $label;
	}
	return $links;
}
add_filter( 'wpseo_breadcrumb_links', 'jardin_filter_yoast_breadcrumb_toasts_hub', 20 );

/**
 * Comme l’ancien bloc thème : pas de fil d’Ariane sur la page d’accueil.
 *
 * @param string               $block_content Sortie du bloc.
 * @param array<string, mixed> $block         Bloc parsé.
 * @return string
 */
function jardin_hide_yoast_breadcrumbs_on_front_page( string $block_content, array $block ): string {
	if ( 'yoast-seo/breadcrumbs' !== ( $block['blockName'] ?? '' ) ) {
		return $block_content;
	}
	if ( ! is_admin() && is_front_page() ) {
		return '';
	}
	return $block_content;
}
add_filter( 'render_block', 'jardin_hide_yoast_breadcrumbs_on_front_page', 10, 2 );
