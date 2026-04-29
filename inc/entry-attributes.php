<?php
/**
 * Inject data-kind / data-note-kind on `.entry` groups inside Query Loops (mockup parity).
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Map a post to the coarse mockup "kind" used by home/journal filters (post | note | event | project).
 *
 * @param \WP_Post|null $post Post.
 * @return string
 */
function jardin_get_entry_data_kind( $post ): string {
	if ( ! $post instanceof WP_Post ) {
		return 'note';
	}
	switch ( $post->post_type ) {
		case 'post':
			return 'post';
		case 'event':
			return 'event';
		default:
			return 'project' === $post->post_type ? 'project' : 'note';
	}
}

/**
 * Granular note_kind for /notes filters and card badges (bookmark, quote, jam, …).
 *
 * @param \WP_Post|null $post Post.
 * @return string
 */
function jardin_get_entry_note_kind( $post ): string {
	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	$pt = (string) $post->post_type;

	if ( 'iwcpt_like' === $pt ) {
		return 'like';
	}

	if ( 'favorite' === $pt ) {
		return '' === trim( (string) $post->post_content ) ? 'bookmark' : 'quote';
	}

	if ( 'beer_checkin' === $pt ) {
		return '' === trim( (string) $post->post_content ) ? 'tasting' : 'review';
	}

	if ( 'listen' === $pt ) {
		return '' === trim( (string) $post->post_content ) ? 'listen' : 'jam';
	}

	if ( 'iwcpt_note' === $pt ) {
		$tax = jardin_get_note_kind_taxonomy_slug();
		if ( $tax ) {
			$terms = get_the_terms( (int) $post->ID, $tax );
			if ( is_array( $terms ) && ! is_wp_error( $terms ) && isset( $terms[0] ) ) {
				return sanitize_key( $terms[0]->slug );
			}
		}
		return 'note';
	}

	if ( 'post' === $pt && has_category( 'til', $post ) ) {
		return 'til';
	}

	return '';
}

/**
 * First registered taxonomy that looks like IndieBlocks note_kind (if any).
 *
 * @return string
 */
function jardin_get_note_kind_taxonomy_slug(): string {
	static $cached = null;
	if ( null !== $cached ) {
		return $cached;
	}
	$cached = '';
	foreach ( array( 'note_kind', 'indieblocks_note_kind', 'ib_note_kind' ) as $slug ) {
		if ( taxonomy_exists( $slug ) ) {
			$cached = $slug;
			break;
		}
	}
	return $cached;
}

/**
 * Add data-* attributes to rendered `.entry` group blocks (post context).
 *
 * @param string       $block_content Block HTML.
 * @param array<mixed> $block         Parsed block.
 * @return string
 */
function jardin_render_block_entry_data_attrs( string $block_content, array $block ): string {
	if ( empty( $block['blockName'] ) || 'core/group' !== $block['blockName'] ) {
		return $block_content;
	}

	$attrs   = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : array();
	$class   = isset( $attrs['className'] ) ? (string) $attrs['className'] : '';
	$classes = preg_split( '/\s+/', $class, -1, PREG_SPLIT_NO_EMPTY );
	if ( ! is_array( $classes ) || ! in_array( 'entry', $classes, true ) ) {
		return $block_content;
	}

	$ctx     = isset( $block['context'] ) && is_array( $block['context'] ) ? $block['context'] : array();
	$post_id = isset( $ctx['postId'] ) ? (int) $ctx['postId'] : 0;
	if ( $post_id <= 0 ) {
		return $block_content;
	}

	$post = get_post( $post_id );
	if ( ! $post ) {
		return $block_content;
	}

	$kind      = jardin_get_entry_data_kind( $post );
	$note_kind = jardin_get_entry_note_kind( $post );

	if ( class_exists( 'WP_HTML_Tag_Processor' ) ) {
		$tags = new WP_HTML_Tag_Processor( $block_content );
		if ( $tags->next_tag( array( 'class_name' => 'entry' ) ) ) {
			$tags->set_attribute( 'data-kind', $kind );
			if ( '' !== $note_kind ) {
				$tags->set_attribute( 'data-note-kind', $note_kind );
			}
		}
		return $tags->get_updated_html();
	}

	return $block_content;
}
add_filter( 'render_block', 'jardin_render_block_entry_data_attrs', 10, 2 );
