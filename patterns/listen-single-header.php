<?php
/**
 * Title: Listen single header
 * Slug: jardin-theme/listen-single-header
 * Categories: text
 * Description: Album art, track metadata, and Last.fm link for a single listen post.
 * Inserter: no
 *
 * @package Jardin_Theme */

$pid    = get_the_ID();
$track  = esc_html( (string) get_post_meta( $pid, '_sj_track_name', true ) );
$artist = esc_html( (string) get_post_meta( $pid, '_sj_artist_name', true ) );
$album  = esc_html( (string) get_post_meta( $pid, '_sj_album_name', true ) );
$art_local = esc_url( (string) get_post_meta( $pid, '_sj_album_art_local_url', true ) );
$art_remote = esc_url( (string) get_post_meta( $pid, '_sj_album_art_url', true ) );
$art       = '' !== $art_local ? $art_local : $art_remote;
$lfm    = esc_url( (string) get_post_meta( $pid, '_sj_lastfm_url', true ) );
$spotify = esc_url( (string) get_post_meta( $pid, '_sj_spotify_url', true ) );
$youtube = esc_url( (string) get_post_meta( $pid, '_sj_youtube_url', true ) );
$length  = absint( get_post_meta( $pid, '_sj_track_length', true ) );
$at     = (string) get_post_meta( $pid, '_sj_listened_at', true );
$is_jam = strlen( trim( (string) get_post_field( 'post_content', $pid ) ) ) > 0;
if ( function_exists( 'get_term_by' ) && class_exists( 'Jardin_Scrobble_CPT_Listen' ) ) {
	$artist_term = get_term_by( 'name', html_entity_decode( $artist, ENT_QUOTES, get_bloginfo( 'charset' ) ), Jardin_Scrobble_CPT_Listen::TAX_ARTIST );
	$artist_link = ( $artist_term instanceof WP_Term ) ? get_term_link( $artist_term ) : '';
} else {
	$artist_link = '';
}

$length_str = '';
if ( $length > 0 ) {
	$minutes    = (int) floor( $length / 60 );
	$seconds    = $length % 60;
	$length_str = sprintf( '%d:%02d', $minutes, $seconds );
}

$time_str = '';
if ( '' !== $at ) {
	$ts       = (int) strtotime( $at );
	$time_str = sprintf(
		/* translators: 1: formatted date, 2: relative time */
		__( '%1$s · %2$s ago', 'jardin-theme' ),
		wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $ts ),
		human_time_diff( $ts, time() )
	);
}
?>
<div class="sj-single-listen">
	<?php if ( '' !== $art ) : ?>
		<img class="sj-listen-art" src="<?php echo $art; ?>" alt="<?php echo esc_attr( $album ); ?>" width="120" height="120" decoding="async" fetchpriority="high" loading="eager" />
	<?php endif; ?>
	<div class="sj-listen-meta">
		<p class="sj-listen-track"><?php echo $track; ?></p>
		<p class="sj-listen-artist">
			<?php if ( is_string( $artist_link ) && '' !== $artist_link && ! is_wp_error( $artist_link ) ) : ?>
				<a href="<?php echo esc_url( $artist_link ); ?>"><?php echo $artist; ?></a>
			<?php else : ?>
				<?php echo $artist; ?>
			<?php endif; ?>
		</p>
		<?php if ( '' !== $album ) : ?>
			<p class="sj-listen-album"><?php echo $album; ?></p>
		<?php endif; ?>
		<?php if ( '' !== $length_str ) : ?>
			<p class="sj-listen-length"><?php echo esc_html( $length_str ); ?></p>
		<?php endif; ?>
		<?php if ( '' !== $time_str ) : ?>
			<p class="sj-listen-time"><?php echo esc_html( $time_str ); ?></p>
		<?php endif; ?>
		<div class="sj-listen-links">
			<?php if ( '' !== $lfm ) : ?>
				<span class="u-listen-of h-cite"><a class="u-url" href="<?php echo $lfm; ?>" target="_blank" rel="noopener"><?php echo esc_html__( '→ Last.fm', 'jardin-theme' ); ?></a></span>
			<?php endif; ?>
			<?php if ( '' !== $spotify ) : ?>
				<a href="<?php echo $spotify; ?>" target="_blank" rel="noopener"><?php echo esc_html__( '→ Spotify', 'jardin-theme' ); ?></a>
			<?php endif; ?>
			<?php if ( '' !== $youtube ) : ?>
				<a href="<?php echo $youtube; ?>" target="_blank" rel="noopener"><?php echo esc_html__( '→ YouTube', 'jardin-theme' ); ?></a>
			<?php endif; ?>
		</div>
		<?php if ( $is_jam ) : ?>
			<span class="sj-listen-jam-badge"><?php echo esc_html__( 'jam', 'jardin-theme' ); ?></span>
		<?php endif; ?>
	</div>
</div>
