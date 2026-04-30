<?php
/**
 * Render project technical metadata.
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

$project_id = get_the_ID();
if ( ! $project_id ) {
	return '';
}

$version = (string) get_post_meta( $project_id, 'current_version', true );
$license = (string) get_post_meta( $project_id, 'license', true );
$stack   = (string) get_post_meta( $project_id, 'stack_label', true );
$repo    = (string) get_post_meta( $project_id, 'repo_url', true );

if ( '' === $license ) {
	$license = 'GPL-2.0-or-later';
}
if ( '' === $version ) {
	$version = 'n/a';
}

ob_start();
?>
<div class="project-header-meta">
	<div class="project-stat">
		<span class="project-stat-label"><?php esc_html_e( 'version', 'jardin-theme' ); ?></span>
		<span class="project-stat-value"><?php echo esc_html( $version ); ?></span>
	</div>
	<div class="project-stat">
		<span class="project-stat-label"><?php esc_html_e( 'licence', 'jardin-theme' ); ?></span>
		<span class="project-stat-value"><?php echo esc_html( $license ); ?></span>
	</div>
	<?php if ( '' !== $stack ) : ?>
		<div class="project-stat">
			<span class="project-stat-label"><?php esc_html_e( 'stack', 'jardin-theme' ); ?></span>
			<span class="project-stat-value"><?php echo esc_html( $stack ); ?></span>
		</div>
	<?php endif; ?>
	<?php if ( '' !== $repo ) : ?>
		<div class="project-stat">
			<span class="project-stat-label"><?php esc_html_e( 'repo', 'jardin-theme' ); ?></span>
			<span class="project-stat-value"><a href="<?php echo esc_url( $repo ); ?>" target="_blank" rel="noopener"><?php echo esc_html( preg_replace( '#^https?://#', '', $repo ) ); ?></a></span>
		</div>
	<?php endif; ?>
</div>
<?php

return (string) ob_get_clean();
