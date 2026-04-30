<?php
/**
 * Inject data-kind / data-note-kind on `.entry` groups inside Query Loops (mockup parity).
 *
 * @package Jardin_Theme */

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
 * Granular note_kind for the activity hub archive filters and card badges (bookmark, quote, jam, …).
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

/**
 * First related post id from event_article meta (array or scalar).
 *
 * @param int $event_id Event post id.
 * @return int
 */
function jardin_get_event_related_post_id( int $event_id ): int {
	$raw = get_post_meta( $event_id, 'event_article', true );

	if ( is_array( $raw ) ) {
		foreach ( $raw as $candidate ) {
			$id = (int) $candidate;
			if ( $id > 0 ) {
				return $id;
			}
		}
		return 0;
	}

	$id = (int) $raw;
	return $id > 0 ? $id : 0;
}

/**
 * Build fallback summary for event archive cards.
 *
 * @param \WP_Post $post Event post.
 * @return string
 */
function jardin_get_event_archive_summary( WP_Post $post ): string {
	$content = trim( (string) $post->post_content );
	if ( '' !== $content ) {
		return wp_trim_words( wp_strip_all_tags( strip_shortcodes( $content ) ), 42, '…' );
	}

	$related_id = jardin_get_event_related_post_id( (int) $post->ID );
	if ( $related_id > 0 ) {
		$related = get_post( $related_id );
		if ( $related instanceof WP_Post ) {
			$related_excerpt = trim( (string) $related->post_excerpt );
			if ( '' !== $related_excerpt ) {
				return wp_trim_words( wp_strip_all_tags( $related_excerpt ), 42, '…' );
			}

			$related_content = trim( (string) $related->post_content );
			if ( '' !== $related_content ) {
				return wp_trim_words( wp_strip_all_tags( strip_shortcodes( $related_content ) ), 42, '…' );
			}
		}
	}

	return '';
}

/**
 * Fallback text for empty event excerpts in archive cards.
 *
 * @param string       $block_content Block HTML.
 * @param array<mixed> $block         Parsed block.
 * @return string
 */
function jardin_render_event_excerpt_fallback( string $block_content, array $block ): string {
	if ( empty( $block['blockName'] ) || 'core/post-excerpt' !== $block['blockName'] ) {
		return $block_content;
	}

	$attrs      = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : array();
	$class_name = isset( $attrs['className'] ) ? (string) $attrs['className'] : '';
	$classes    = preg_split( '/\s+/', $class_name, -1, PREG_SPLIT_NO_EMPTY );

	if ( ! is_array( $classes ) || ! in_array( 'entry-excerpt', $classes, true ) ) {
		return $block_content;
	}

	$ctx     = isset( $block['context'] ) && is_array( $block['context'] ) ? $block['context'] : array();
	$post_id = isset( $ctx['postId'] ) ? (int) $ctx['postId'] : 0;
	if ( $post_id <= 0 || 'event' !== get_post_type( $post_id ) ) {
		return $block_content;
	}

	$current_text = trim( wp_strip_all_tags( $block_content ) );
	if ( '' !== $current_text ) {
		return $block_content;
	}

	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post ) {
		return $block_content;
	}

	$summary = jardin_get_event_archive_summary( $post );
	if ( '' === $summary ) {
		return $block_content;
	}

	return sprintf(
		'<p class="%1$s">%2$s</p>',
		esc_attr( trim( 'entry-excerpt wp-block-post-excerpt__excerpt ' . $class_name ) ),
		esc_html( $summary )
	);
}
add_filter( 'render_block', 'jardin_render_event_excerpt_fallback', 11, 2 );
