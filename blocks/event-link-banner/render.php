<?php
/**
 * Banner linking to the related event when event_linked_post references this post.
 *
 * @package Jardin
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'jardin_events_find_event_for_recap_post' ) ) {
	return '';
}

$post_id = get_the_ID();
if ( ! $post_id || 'post' !== get_post_type( $post_id ) ) {
	return '';
}

$event = jardin_events_find_event_for_recap_post( $post_id );
if ( ! $event instanceof WP_Post ) {
	return '';
}

$url   = get_permalink( $event );
$title = get_the_title( $event );
if ( ! $url ) {
	return '';
}

ob_start();
?>
<aside class="event-link-banner" aria-label="<?php esc_attr_e( 'Linked event', 'jardin' ); ?>">
	<span class="event-link-banner-label"><?php esc_html_e( 'This article is the recap of an event', 'jardin' ); ?></span>
	<span class="event-link-banner-event">
		→ <a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $title ); ?></a>
	</span>
</aside>
<?php
return ob_get_clean();
