<?php
/**
 * Render project inline meta row.
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

$post_id = get_the_ID();
if ( ! $post_id ) {
	return '';
}

$updated = get_the_modified_date( 'c', $post_id );
$status  = jardin_projects_get_status_label( (int) $post_id );
$slug    = jardin_projects_get_status_slug( (int) $post_id );
$terms   = get_the_terms( $post_id, 'post_tag' );

ob_start();
?>
<div class="post-meta">
	<?php if ( '' !== (string) $updated ) : ?>
		<time class="dt-updated" datetime="<?php echo esc_attr( (string) $updated ); ?>" title="<?php echo esc_attr( __( 'Last updated', 'jardin-theme' ) ); ?>">
			<?php
			printf(
				/* translators: %s is a localized date. */
				esc_html__( 'Updated %s', 'jardin-theme' ),
				esc_html( get_the_modified_date( '', $post_id ) )
			);
			?>
		</time>
	<?php endif; ?>
	<?php if ( '' !== (string) $status ) : ?>
		<span class="post-meta-sep">·</span>
		<span class="post-meta-taxos">
			<span class="taxo-inline taxo-cat project-status <?php echo esc_attr( jardin_projects_status_class( $slug ) ); ?>">
				<?php echo esc_html( $status ); ?>
			</span>
			<?php if ( is_array( $terms ) && ! is_wp_error( $terms ) ) : ?>
				<?php foreach ( array_slice( $terms, 0, 4 ) as $term ) : ?>
					<span class="taxo-inline taxo-tag">#<?php echo esc_html( $term->slug ); ?></span>
				<?php endforeach; ?>
			<?php endif; ?>
		</span>
	<?php endif; ?>
</div>
<?php

return (string) ob_get_clean();
