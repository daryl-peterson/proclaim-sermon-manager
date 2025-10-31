<?php
/**
 * Series list.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Data\Sermon;


// These must be defined before including psm-check-args.
$template = str_replace( '.php', '', basename( __FILE__ ) );
$required = array( 'list' );

$result = require_once 'psm-check-args.php';
if ( ! $result ) {
	return;
}

$list = $args['list'];

$play   = get_query_var( 'play' );
$player = get_query_var( 'player' );
if ( $play && ! empty( $play ) ) {

	if ( key_exists( $play, $list ) ) {
		$item_first = $list[ $play ];
	} else {
		$first_key  = array_key_first( $list );
		$item_first = $list[ $first_key ];
	}
} else {
	$first_key  = array_key_first( $list );
	$item_first = $list[ $first_key ];
}


/**
 * Sermon Object.
 *
 * @var Sermon $item_first Post object.
 */

$post_first = $item_first->post;
$preacher   = $item_first->preacher;
$meta       = $item_first->meta;
$date       = $meta->date();

$poster = get_sermon_image_url( ImageSize::SERMON_WIDE, true, true, $post_first );
$cnt    = 0;
?>
<div id="drppsm-archive">

	<div class="header">
		<h2 class="title">
			<?php echo esc_html( $post_first->post_title ); ?>
		</h2>
		<?php if ( $preacher ) : ?>
			<h3 class="meta">
				<?php echo esc_html( $preacher->name() ); ?> - <?php echo esc_html( $date ); ?>
			</h3>
		<?php endif; ?>
	</div>
	<!-- /#drppsm-archive-header -->
	<div class="media">
		<?php

		if ( ( ! $meta->has_video() && $meta->has_audio() ) || ( $meta->has_video() && 'audio' === $player ) ) {
			if ( $poster ) {

				echo '<img src="' . esc_url( $poster ) . '" class="poster" alt="' . esc_attr( $post_first->post_title ) . '" />';

			}
			// phpcs:disable
			echo MediaPlayer::render_audio( $meta->audio );
			// phpcs:enable
		} elseif ( $item_first->meta->has_video() ) {
			// phpcs:disable
			echo MediaPlayer::render_video( $meta->video_link, true, $poster );
			// phpcs:enable
		} else {
			echo '<img src="' . esc_url( $poster ) . '" class="poster" alt="' . esc_attr( $post_first->post_title ) . '" />';
		}
		?>
	</div>
	<!-- /#drppsm-archive-media -->
	<div class="detail">
	<h3 class="more-from">
		More from
	</h3>
		<div class="share-details">
			<ul>

			</ul>
		</div>
	</div>

<?php if ( count( $list ) > 0 ) : ?>
	<table>
		<tbody>
<?php endif; ?>

<?php
foreach ( $list as $item ) :
	++$cnt;
	$tr_class = 'odd';
	if ( 0 === $cnt % 2 ) :
		$tr_class = 'even';
	endif;

	$post_item = $item->post;
	$preacher  = $item->preacher;
	$meta      = $item->meta;


	if ( $post_item->ID === $post_first->ID ) {
		$tr_class = 'active';
	}
	?>
	<tr class="<?php echo esc_attr( $tr_class ); ?>">
		<td class="title-cell">
			<?php echo esc_html( $post_item->post_title ); ?>
		</td>
		<td class="preacher-cell">
			<?php echo esc_html( $preacher->name() ); ?>&nbsp;
		</td>
		<td class="date-cell">
			<?php echo esc_html( $meta->date() ); ?>&nbsp;
		</td>
		<td class="watch-cell">
			<?php
			if ( $item->meta->has_video() ) {
				echo '<a data-id="' . esc_attr( $post_item->ID ) . '" class="drppsm-play-video btn-md"></a>';
			}
			if ( $item->meta->has_audio() ) {
				echo '<a data-id="' . esc_attr( $post_item->ID ) . '" class="drppsm-play-audio btn-md"></a>';
			}
			?>
		</td>
	</tr>


<?php endforeach; ?>

<?php if ( count( $list ) > 0 ) : ?>
		</tbody>
	</table>
<?php endif; ?>

</div>
<!-- /#drppsm-archive -->