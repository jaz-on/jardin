<?php
/**
 * Title: Listen single header
 * Slug: jardin/listen-single-header
 * Categories: text
 * Description: Album art, track metadata, and Last.fm link for a single listen post.
 * Inserter: no
 *
 * @package Jardin
 */

$pid    = get_the_ID();
$track  = esc_html( (string) get_post_meta( $pid, '_sj_track_name', true ) );
$artist = esc_html( (string) get_post_meta( $pid, '_sj_artist_name', true ) );
$album  = esc_html( (string) get_post_meta( $pid, '_sj_album_name', true ) );
$art    = esc_url( (string) get_post_meta( $pid, '_sj_album_art_url', true ) );
$lfm    = esc_url( (string) get_post_meta( $pid, '_sj_lastfm_url', true ) );
$at     = (string) get_post_meta( $pid, '_sj_listened_at', true );
$is_jam = strlen( trim( (string) get_post_field( 'post_content', $pid ) ) ) > 0;

$time_str = '';
if ( '' !== $at ) {
	$ts       = (int) strtotime( $at );
	$time_str = sprintf(
		/* translators: 1: formatted date, 2: relative time */
		__( '%1$s · il y a %2$s', 'jardin' ),
		wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $ts ),
		human_time_diff( $ts, time() )
	);
}
?>
<div class="sj-single-listen">
	<?php if ( '' !== $art ) : ?>
		<img class="sj-listen-art" src="<?php echo $art; ?>" alt="<?php echo esc_attr( $album ); ?>" loading="lazy" width="120" height="120" />
	<?php endif; ?>
	<div class="sj-listen-meta">
		<p class="sj-listen-track"><?php echo $track; ?></p>
		<p class="sj-listen-artist"><?php echo $artist; ?></p>
		<?php if ( '' !== $album ) : ?>
			<p class="sj-listen-album"><?php echo $album; ?></p>
		<?php endif; ?>
		<?php if ( '' !== $time_str ) : ?>
			<p class="sj-listen-time"><?php echo esc_html( $time_str ); ?></p>
		<?php endif; ?>
		<div class="sj-listen-links">
			<?php if ( '' !== $lfm ) : ?>
				<a href="<?php echo $lfm; ?>" target="_blank" rel="noopener"><?php echo esc_html__( '→ Last.fm', 'jardin' ); ?></a>
			<?php endif; ?>
		</div>
		<?php if ( $is_jam ) : ?>
			<span class="sj-listen-jam-badge"><?php echo esc_html__( 'jam', 'jardin' ); ?></span>
		<?php endif; ?>
	</div>
</div>
