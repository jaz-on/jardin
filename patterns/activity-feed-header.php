<?php
/**
 * Title: Activity archive — filters
 * Slug: jardin-theme/activity-feed-header
 * Categories: text
 * Description: note_kind filter pills; base URL starter /activites/ — edit archive slug in Site Editor if needed.
 * Inserter: no
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

$kinds = array(
	array( 'slug' => '', 'label' => __( 'All', 'jardin-theme' ) ),
	array( 'slug' => 'bookmark', 'label' => __( 'bookmark', 'jardin-theme' ) ),
	array( 'slug' => 'quote', 'label' => __( 'quote', 'jardin-theme' ) ),
	array( 'slug' => 'listen', 'label' => __( 'listen', 'jardin-theme' ) ),
	array( 'slug' => 'jam', 'label' => __( 'jam', 'jardin-theme' ) ),
	array( 'slug' => 'tasting', 'label' => __( 'tasting', 'jardin-theme' ) ),
	array( 'slug' => 'review', 'label' => __( 'review', 'jardin-theme' ) ),
	array( 'slug' => 'reply', 'label' => __( 'reply', 'jardin-theme' ) ),
	array( 'slug' => 'til', 'label' => __( 'til', 'jardin-theme' ) ),
	array( 'slug' => 'note', 'label' => __( 'note', 'jardin-theme' ) ),
);

$base = trailingslashit( home_url( '/activites/' ) );

// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$current = isset( $_GET['note_kind'] ) ? sanitize_key( wp_unslash( $_GET['note_kind'] ) ) : '';

$buttons = array();
foreach ( $kinds as $row ) {
	$slug   = (string) $row['slug'];
	$type   = $slug ? $slug : 'all';
	$href   = '' === $slug ? remove_query_arg( 'note_kind', $base ) : add_query_arg( 'note_kind', $slug, $base );
	$active = ( '' === $current && '' === $slug ) || ( '' !== $slug && $current === $slug );
	$buttons[] = sprintf(
		'<a class="ff-btn %4$s" href="%1$s" data-type="%2$s"%5$s>%3$s</a>',
		esc_url( $href ),
		esc_attr( $type ),
		esc_html( (string) $row['label'] ),
		$active ? 'active' : '',
		$active ? ' aria-current="page"' : ''
	);
}

$nav_inner = implode( '', $buttons );
$dfilter   = '' === $current ? 'all' : $current;

?>
<!-- wp:group {"align":"wide","className":"feed-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide feed-header">
	<!-- wp:html -->
	<h2 class="wp-block-heading"><?php esc_html_e( 'Filter activity feed', 'jardin-theme' ); ?></h2>
	<nav class="feed-filters notes-filters activity-archive-filters notes-archive-filters" role="navigation" aria-label="<?php echo esc_attr__( 'Filter by entry type', 'jardin-theme' ); ?>" data-filter="<?php echo esc_attr( $dfilter ); ?>">
		<?php echo $nav_inner; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</nav>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->
