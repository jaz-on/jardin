<?php
/**
 * Title: Activity archive — filters
 * Slug: jardin-theme/activity-feed-header
 * Categories: text
 * Description: note_kind filter pills for the iwcpt_note archive (hub /activites/ or /activities/).
 * Inserter: no
 *
 * @package Jardin_Theme */

$kinds = array(
	array( 'slug' => '', 'label' => __( 'tous', 'jardin-theme' ) ),
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

$base = '';
if ( function_exists( 'jardin_get_activity_archive_url' ) ) {
	$base = jardin_get_activity_archive_url();
} elseif ( function_exists( 'get_post_type_archive_link' ) ) {
	$base = (string) get_post_type_archive_link( 'iwcpt_note' );
}
if ( ! $base ) {
	$base = trailingslashit( home_url( '/' . ( function_exists( 'jardin_get_activity_path_segment' ) ? jardin_get_activity_path_segment() : 'activites' ) ) );
}

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
<!-- wp:html -->
<div class="feed-header">
	<h2 class="wp-block-heading"><?php esc_html_e( 'Filtrer les activités', 'jardin-theme' ); ?></h2>
	<nav class="feed-filters notes-filters activity-archive-filters notes-archive-filters" role="navigation" aria-label="<?php echo esc_attr__( 'Filtrer par type d’entrée', 'jardin-theme' ); ?>" data-filter="<?php echo esc_attr( $dfilter ); ?>">
		<?php echo $nav_inner; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</nav>
</div>
<!-- /wp:html -->
