<?php
/**
 * jardin-theme/post-engage — syndication (syndication-links) + webmention comments.
 *
 * @package Jardin_Theme
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$post_id = 0;
if ( $block instanceof WP_Block && ! empty( $block->context['postId'] ) ) {
	$post_id = (int) $block->context['postId'];
} elseif ( is_singular() ) {
	$post_id = (int) get_queried_object_id();
}
$post = $post_id ? get_post( $post_id ) : null;
if ( ! $post ) {
	return '';
}

$display_mode = ( isset( $attributes['displayMode'] ) && 'compact' === $attributes['displayMode'] ) ? 'compact' : 'full';

$raw  = apply_filters( 'syndication_links', array(), $post->ID );
$rows = is_array( $raw ) ? $raw : array();
$out  = array( 'bluesky' => null, 'mastodon' => null );

foreach ( $rows as $k => $row ) {
	$url = '';
	if ( is_string( $row ) && filter_var( $row, FILTER_VALIDATE_URL ) ) {
		$url = $row;
	} elseif ( is_array( $row ) && ! empty( $row['url'] ) && is_string( $row['url'] ) && filter_var( $row['url'], FILTER_VALIDATE_URL ) ) {
		$url = $row['url'];
	}
	if ( ! $url ) {
		continue;
	}
	$url  = esc_url( $url );
	$low  = function_exists( 'mb_strtolower' ) ? mb_strtolower( $url, 'UTF-8' ) : strtolower( $url );
	$lab  = is_string( $k ) && $k && ! is_numeric( $k ) ? (string) $k : (string) wp_parse_url( $url, PHP_URL_HOST );
	if ( is_array( $row ) && ! empty( $row['name'] ) && is_string( $row['name'] ) ) {
		$lab = $row['name'];
	}
	$dest = null;
	if ( ( false !== strpos( $low, 'bsky' ) || false !== strpos( $low, 'bluesky' ) || false !== strpos( $low, 'bsky.app' ) || false !== strpos( $low, 'api.bsky' ) ) && null === $out['bluesky'] ) {
		$dest = 'bluesky';
	} elseif ( null === $out['mastodon'] && ( false !== strpos( $low, 'mastodon' ) || ( false !== strpos( $low, '/@' ) && false === strpos( $low, 'bsky' ) && false === strpos( $low, 'bluesky' ) ) ) ) {
		$dest = 'mastodon';
	}
	if ( $dest ) {
		$out[ $dest ] = array( 'url' => $url, 'label' => $lab );
	}
}
$has_cards = ( null !== $out['bluesky'] || null !== $out['mastodon'] );

$wm_count = (int) get_comments(
	array(
		'post_id' => (int) $post->ID,
		'status'  => 'approve',
		'type'    => 'webmention',
		'count'   => true,
	)
);

$wm = array();
if ( 'full' === $display_mode && $wm_count > 0 ) {
	$wm = get_comments(
		array(
			'post_id' => (int) $post->ID,
			'status'  => 'approve',
			'type'    => 'webmention',
			'orderby' => 'comment_date_gmt',
			'order'   => 'ASC',
			'number'  => 40,
		)
	);
	$wm = is_array( $wm ) ? $wm : array();
}

$has_wm = $wm_count > 0;

$editor_hint = ( 'full' === $display_mode && ! $has_cards && ! empty( $rows ) && current_user_can( 'edit_post', (int) $post->ID ) );

if ( ! $has_cards && ! $has_wm && ! $editor_hint ) {
	return '';
}

$engage_id = 'jardin-post-engage-' . (int) $post->ID;
$permalink = get_permalink( $post );
$permalink = is_string( $permalink ) ? $permalink : '';

if ( 'compact' === $display_mode ) {
	$posse_parts = array();
	$order       = array( 'bluesky' => _x( 'BS', 'abbrev syndication Bluesky', 'jardin-theme' ), 'mastodon' => _x( 'Fed', 'abbrev syndication fediverse', 'jardin-theme' ) );
	foreach ( $order as $d => $abbr ) {
		$row = $out[ $d ] ?? null;
		if ( ! is_array( $row ) || empty( $row['url'] ) ) {
			continue;
		}
		$posse_parts[] = sprintf(
			'<a class="jardin-theme-post-engage__posse-link" rel="external noopener noreferrer" href="%1$s">%2$s<span class="screen-reader-text"> %3$s</span></a>',
			esc_url( (string) $row['url'] ),
			esc_html( (string) $abbr ),
			esc_html( 'bluesky' === $d ? _x( 'Bluesky', 'syndication service name', 'jardin-theme' ) : _x( 'Mastodon', 'syndication service name', 'jardin-theme' ) )
		);
	}
	$has_posse = ! empty( $posse_parts );

	ob_start();
	?>
<div class="jardin-theme-post-engage jardin-theme-post-engage--compact has-xs-font-size" data-post-engage id="<?php echo esc_attr( $engage_id ); ?>">
	<p class="jardin-theme-post-engage__teaser">
		<?php if ( $wm_count > 0 && '' !== $permalink ) : ?>
			<?php
			$wm_line = sprintf(
				/* translators: %d: number of webmention comments */
				_n( '%d reaction', '%d reactions', $wm_count, 'jardin-theme' ),
				$wm_count
			);
			?>
			<a class="jardin-theme-post-engage__pill" href="<?php echo esc_url( $permalink . '#' . $engage_id ); ?>"><?php echo esc_html( $wm_line ); ?></a>
			<?php if ( $has_posse ) : ?>
				<span class="jardin-theme-post-engage__sep" aria-hidden="true"> · </span>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( $has_posse ) : ?>
			<span class="jardin-theme-post-engage__posse" aria-label="<?php echo esc_attr__( 'Syndication', 'jardin-theme' ); ?>"><?php echo implode( '', $posse_parts ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<?php endif; ?>
	</p>
</div>
	<?php
	return (string) ob_get_clean();
}

ob_start();
?>
<section class="jardin-theme-post-engage" data-post-engage id="<?php echo esc_attr( $engage_id ); ?>">
	<?php if ( $has_cards ) : ?>
	<header class="jardin-theme-post-engage__head">
		<h2 class="jardin-theme-post-engage__title has-sm-font-size"><?php echo esc_html__( 'On the fediverse', 'jardin-theme' ); ?></h2>
	</header>
		<?php
		$order = array( 'bluesky' => 'bsky', 'mastodon' => 'masto' );
		foreach ( $order as $d => $brand ) {
			$row = $out[ $d ] ?? null;
			if ( ! is_array( $row ) || empty( $row['url'] ) ) {
				continue;
			}
			$is_b = ( 'bluesky' === $d );
			$btn  = $is_b
				? _x( 'View on Bluesky', 'syndication card CTA', 'jardin-theme' )
				: _x( 'View on Mastodon', 'syndication card CTA', 'jardin-theme' );
			$cls  = 'jardin-theme-post-engage__card ' . ( $is_b ? 'is-bluesky' : 'is-mastodon' );
			?>
	<article class="<?php echo esc_attr( $cls ); ?>" data-brand="<?php echo esc_attr( (string) $brand ); ?>">
		<p class="jardin-theme-post-engage__label has-xs-font-size"><?php echo esc_html( (string) ( $row['label'] ?? '' ) ); ?></p>
		<a class="jardin-theme-post-engage__cta" rel="external noopener noreferrer" href="<?php echo esc_url( (string) $row['url'] ); ?>"><?php echo esc_html( (string) $btn ); ?> <span aria-hidden="true" class="jardin-theme-post-engage__ext">↗</span></a>
	</article>
			<?php
		}
		?>
	<?php elseif ( $editor_hint ) : ?>
	<p class="jardin-theme-post-engage__dev has-xs-font-size has-text-muted-color"><?php esc_html_e( 'No Bluesky or Mastodon syndication link was auto-detected on this post.', 'jardin-theme' ); ?></p>
	<?php endif; ?>

	<?php if ( $has_wm ) : ?>
	<header class="jardin-theme-post-engage__head jardin-theme-post-engage__head--replies">
		<?php
		$count = count( $wm );
		/* translators: %d: number of webmention comments */
		$line = (string) sprintf( _n( '%d mention or reply', '%d mentions and replies', $count, 'jardin-theme' ), (int) $count );
		?>
		<h2 class="jardin-theme-post-engage__title has-sm-font-size"><?php echo esc_html__( 'Mentions and replies', 'jardin-theme' ); ?></h2>
		<p class="jardin-theme-post-engage__stats has-xs-font-size has-text-muted-color"><?php echo esc_html( $line ); ?></p>
	</header>
	<ol class="jardin-theme-wm__list" role="list">
		<?php
		foreach ( $wm as $c ) :
			if ( ! is_object( $c ) || ! isset( $c->comment_ID, $c->comment_content ) ) {
				continue;
			}
			$cid = (int) $c->comment_ID;
			$u   = (string) get_comment_author_url( $cid );
			$a   = (string) get_comment_author( $cid );
			$u   = ( $u && filter_var( $u, FILTER_VALIDATE_URL ) ) ? $u : '#';
			$ex  = esc_html( wp_trim_words( wp_strip_all_tags( (string) $c->comment_content ), 36, '…' ) );
			?>
		<li class="jardin-theme-wm__li">
			<article class="h-cite" id="webmention-<?php echo (int) $c->comment_ID; ?>">
				<footer class="jardin-theme-wm__meta has-xs-font-size">
					<a class="jardin-theme-wm__author" rel="ugc" href="<?php echo esc_url( $u ); ?>"><span class="p-name"><?php echo esc_html( $a ? $a : __( 'Someone', 'jardin-theme' ) ); ?></span></a>
				</footer>
				<div class="jardin-theme-wm__excerpt e-content has-xs-font-size"><?php echo $ex; // phpcs:ignore WordPress.Security.EscapeOutput -- esc_html above. ?></div>
			</article>
		</li>
		<?php endforeach; ?>
	</ol>
	<?php endif; ?>
</section>
<?php
return (string) ob_get_clean();
