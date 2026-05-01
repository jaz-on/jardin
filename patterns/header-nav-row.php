<?php
/**
 * Title: Header — nav row (primary nav + mobile drawer tools)
 * Slug: jardin-theme/header-nav-row
 * Categories: header
 * Description: Primary Navigation + drawer utilities. CPT links use real archive URLs (`get_post_type_archive_link`) so Polylang and slug filters stay correct.
 * Inserter: no
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

$home_path = static function ( string $path ): string {
	return trailingslashit( esc_url( home_url( $path ) ) );
};

$events_url   = jardin_theme_get_post_type_archive_link( 'event' );
$events_url   = $events_url ? trailingslashit( esc_url( $events_url ) ) : $home_path( '/evenements/' );
$events_label = jardin_theme_archive_path_label( 'event' ) ?: '/evenements';

$projects_url   = jardin_theme_get_post_type_archive_link( 'project' );
$projects_url   = $projects_url ? trailingslashit( esc_url( $projects_url ) ) : $home_path( '/projets/' );
$projects_label = jardin_theme_archive_path_label( 'project' ) ?: '/projets';

?>
<!-- wp:group {"className":"site-row site-row-nav","layout":{"type":"flex","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-group site-row site-row-nav">
	<!-- wp:navigation {"className":"primary jardin-theme-primary-nav","overlayMenu":"never","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"left","orientation":"horizontal","flexWrap":"nowrap"}} -->
		<?php echo jardin_theme_navigation_link_block( '/journal', $home_path( '/journal/' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo jardin_theme_navigation_link_block( '/articles', $home_path( '/articles/' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo jardin_theme_navigation_link_block( '/activites', $home_path( '/activites/' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo jardin_theme_navigation_link_block( $events_label, $events_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo jardin_theme_navigation_link_block( $projects_label, $projects_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo jardin_theme_navigation_link_block( '/maintenant', $home_path( '/maintenant/' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo jardin_theme_navigation_link_block( '/a-propos', $home_path( '/a-propos/' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<!-- /wp:navigation -->

	<!-- wp:jardin-theme/header-utilities {"variant":"drawer"} /-->
</div>
<!-- /wp:group -->
