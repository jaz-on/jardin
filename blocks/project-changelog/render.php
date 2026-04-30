<?php
/**
 * Render project changelog timeline.
 *
 * @package Jardin_Theme
 */

defined( 'ABSPATH' ) || exit;

$project_id = get_the_ID();
if ( ! $project_id ) {
	return '';
}

$limit = isset( $attributes['limit'] ) ? max( 1, min( 50, (int) $attributes['limit'] ) ) : 20;
$sync  = jardin_projects_sync_service();
$items = array_slice( $sync->get_project_changelog( $project_id ), 0, $limit );
$repo  = (string) get_post_meta( $project_id, 'repo_url', true );

ob_start();
?>
<section class="project-changelog">
	<h2><?php esc_html_e( 'Historique des versions', 'jardin-theme' ); ?></h2>
	<?php if ( ! empty( $items ) ) : ?>
		<ul class="changelog-list">
			<?php foreach ( $items as $item ) : ?>
				<li>
					<span class="cl-version"><?php echo esc_html( isset( $item['version_tag'] ) ? (string) $item['version_tag'] : '' ); ?></span>
					<time>
						<?php
						$published = isset( $item['published_at'] ) ? (string) $item['published_at'] : '';
						if ( '' !== $published ) {
							$ts = strtotime( $published );
							echo $ts ? esc_html( wp_date( get_option( 'date_format' ), $ts ) ) : esc_html( $published );
						} else {
							esc_html_e( 'date inconnue', 'jardin-theme' );
						}
						?>
					</time>
					<span class="cl-summary"><?php echo esc_html( isset( $item['summary'] ) ? (string) $item['summary'] : '' ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<p class="u-text-meta-sm u-italic"><?php esc_html_e( 'Aucun changelog synchronisé pour le moment.', 'jardin-theme' ); ?></p>
	<?php endif; ?>
	<?php if ( '' !== $repo ) : ?>
		<p class="u-text-meta-sm"><a href="<?php echo esc_url( $repo ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Voir le repository GitHub complet →', 'jardin-theme' ); ?></a></p>
	<?php endif; ?>
</section>
<?php

return (string) ob_get_clean();
