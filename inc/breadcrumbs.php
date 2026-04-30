<?php
/**
 * Breadcrumb rendering.
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Prefix breadcrumb labels with the visual slash marker.
 *
 * @param string $label Raw label.
 * @return string
 */
function jardin_breadcrumb_label( string $label ): string {
	$label = trim( wp_strip_all_tags( $label ) );
	return '' === $label ? '' : '/' . $label;
}

/**
 * Resolve the dynamic home label from configured front page.
 *
 * @return string
 */
function jardin_breadcrumb_home_label(): string {
	$front_page_id = (int) get_option( 'page_on_front' );

	if ( $front_page_id > 0 ) {
		$title = get_the_title( $front_page_id );
		if ( is_string( $title ) && '' !== trim( $title ) ) {
			return $title;
		}
	}

	$name = get_bloginfo( 'name' );
	return is_string( $name ) && '' !== trim( $name ) ? $name : home_url( '/' );
}

/**
 * Build linked ancestors for hierarchical terms.
 *
 * @param WP_Term $term Term object.
 * @return array<int,array{label:string,url:string,current:bool}>
 */
function jardin_breadcrumb_term_ancestors( WP_Term $term ): array {
	$items     = array();
	$ancestors = array_reverse( get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' ) );

	foreach ( $ancestors as $ancestor_id ) {
		$ancestor = get_term( (int) $ancestor_id, $term->taxonomy );
		if ( ! $ancestor instanceof WP_Term ) {
			continue;
		}

		$link = get_term_link( $ancestor );
		if ( is_wp_error( $link ) ) {
			continue;
		}

		$items[] = array(
			'label'   => $ancestor->name,
			'url'     => $link,
			'current' => false,
		);
	}

	return $items;
}

/**
 * Build linked ancestors for hierarchical singular objects.
 *
 * @param WP_Post $post Post object.
 * @return array<int,array{label:string,url:string,current:bool}>
 */
function jardin_breadcrumb_post_ancestors( WP_Post $post ): array {
	$items = array();

	if ( ! is_post_type_hierarchical( $post->post_type ) ) {
		return $items;
	}

	$ancestor_ids = array_reverse( get_post_ancestors( $post ) );

	foreach ( $ancestor_ids as $ancestor_id ) {
		$ancestor = get_post( (int) $ancestor_id );
		if ( ! $ancestor instanceof WP_Post ) {
			continue;
		}

		$link = get_permalink( $ancestor );
		if ( ! is_string( $link ) || '' === $link ) {
			continue;
		}

		$items[] = array(
			'label'   => get_the_title( $ancestor ),
			'url'     => $link,
			'current' => false,
		);
	}

	return $items;
}

/**
 * Add post type archive crumb when available.
 *
 * @param string $post_type Post type key.
 * @return array{label:string,url:string,current:bool}|null
 */
function jardin_breadcrumb_post_type_archive_item( string $post_type ): ?array {
	$archive_url = get_post_type_archive_link( $post_type );
	$object      = get_post_type_object( $post_type );

	if ( ! is_string( $archive_url ) || '' === $archive_url || ! $object ) {
		return null;
	}

	return array(
		'label'   => $object->labels->name,
		'url'     => $archive_url,
		'current' => false,
	);
}

/**
 * Build a compact breadcrumb trail for all views except front page.
 *
 * @return string
 */
function jardin_render_breadcrumb(): string {
	if ( is_admin() || is_front_page() ) {
		return '';
	}

	$items = array(
		array(
			'label'   => jardin_breadcrumb_home_label(),
			'url'     => home_url( '/' ),
			'current' => false,
		),
	);

	if ( is_home() ) {
		$posts_page_id = (int) get_option( 'page_for_posts' );
		$items[]       = array(
			'label'   => $posts_page_id > 0 ? get_the_title( $posts_page_id ) : get_the_archive_title(),
			'url'     => '',
			'current' => true,
		);
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			if ( 'page' !== $post->post_type ) {
				$archive_item = jardin_breadcrumb_post_type_archive_item( $post->post_type );
				if ( is_array( $archive_item ) ) {
					$items[] = $archive_item;
				}
			}

			$items = array_merge( $items, jardin_breadcrumb_post_ancestors( $post ) );

			$items[] = array(
				'label'   => get_the_title( $post ),
				'url'     => '',
				'current' => true,
			);
		}
	} elseif ( is_post_type_archive() ) {
		$post_type = get_query_var( 'post_type' );
		$post_type = is_array( $post_type ) ? reset( $post_type ) : $post_type;

		if ( is_string( $post_type ) && '' !== $post_type ) {
			$object = get_post_type_object( $post_type );
			if ( $object ) {
				$items[] = array(
					'label'   => $object->labels->name,
					'url'     => '',
					'current' => true,
				);
			}
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$taxonomy = get_taxonomy( $term->taxonomy );
			if ( $taxonomy ) {
				$items[] = array(
					'label'   => $taxonomy->labels->name,
					'url'     => '',
					'current' => false,
				);
			}

			$items = array_merge( $items, jardin_breadcrumb_term_ancestors( $term ) );

			$items[] = array(
				'label'   => $term->name,
				'url'     => '',
				'current' => true,
			);
		}
	} elseif ( is_author() || is_date() || is_search() || is_404() || is_archive() ) {
		$items[] = array(
			'label'   => get_the_archive_title(),
			'url'     => '',
			'current' => true,
		);
	}

	$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
	if ( $paged > 1 ) {
		$items[] = array(
			'label'   => (string) $paged,
			'url'     => '',
			'current' => true,
		);
	}

	$items = array_values(
		array_filter(
			$items,
			static function ( array $item ): bool {
				return isset( $item['label'] ) && '' !== trim( (string) $item['label'] );
			}
		)
	);

	if ( count( $items ) <= 1 ) {
		return '';
	}

	$last_index = count( $items ) - 1;

	$segments = array();
	foreach ( $items as $index => $item ) {
		$label = jardin_breadcrumb_label( (string) $item['label'] );
		if ( '' === $label ) {
			continue;
		}

		$is_current = $index === $last_index || ! empty( $item['current'] );
		$url        = isset( $item['url'] ) ? (string) $item['url'] : '';

		if ( ! $is_current && '' !== $url ) {
			$segments[] = sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( $url ),
				esc_html( $label )
			);
			continue;
		}

		$segments[] = sprintf(
			'<span class="crumb-current">%s</span>',
			esc_html( $label )
		);
	}

	if ( count( $segments ) <= 1 ) {
		return '';
	}

	return '<p class="breadcrumb">' . implode( ' <span class="crumb-sep">›</span> ', $segments ) . '</p>';
}
