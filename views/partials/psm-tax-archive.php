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

defined( 'ABSPATH' ) || exit;

$template = str_replace( '.php', '', basename( __FILE__ ) );
$failure  = '<p><b>' . DRPPSM_TITLE . '</b>: Partial "<i>' . esc_html( $template ) . '</i>" loaded incorrectly.</p>';

if ( ! isset( $args ) || ! is_array( $args ) ) {
	Logger::error( 'Args variable does not exist. Template : ' . $template );
	render_html( $failure );
	return;
}

if ( ! has_keys( array( 'list' ), $args ) ) {
	render_html( $failure );
	return;
}
$list = $args['list'];

$play = get_query_var( 'play' );
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
 * @var \WP_Post $item_first Post object.
 */


$preacher = null;
if ( isset( $item_first->drppsm_preacher ) ) {
	$preacher = $item_first->drppsm_preacher->name;
}

$date = null;
if ( isset( $item_first->meta->date ) ) {
	$date = $item_first->meta->date;
}


$poster = get_sermon_image_url( ImageSize::SERMON_WIDE, true, true, $item_first );
Logger::debug(
	array(
		'SERMON' => $item_first,
		'POSTER' => $poster,
	)
);


$cnt = 0;
?>
<div id="drppsm-archive">

	<div class="header">
		<h2 class="title">
			<?php echo esc_html( $item_first->post_title ); ?>
		</h2>
		<?php if ( $preacher ) : ?>
			<h3 class="meta">
				<?php echo esc_html( $preacher ); ?> - <?php echo esc_html( $date ); ?>
			</h3>
		<?php endif; ?>
	</div>
	<!-- /#drppsm-archive-header -->
	<div class="media">
		<?php
		if ( isset( $item_first->meta->video_link ) ) {

			echo MediaPlayer::render_video( $item_first->meta->video_link, true, $poster );
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
	if ( $cnt % 2 === 0 ) :
		$tr_class = 'even';
	endif;

	if ( $item->ID === $item_first->ID ) {
		$tr_class = 'active';
	}
	?>
	<tr class="<?php echo esc_attr( $tr_class ); ?>">
		<td class="title-cell">
			<?php echo esc_html( $item->post_title ); ?>
		</td>

		<td class="preacher-cell">
			<?php
			if ( isset( $item->drppsm_preacher ) ) {
				echo esc_html( $item->drppsm_preacher->name );
			}
			?>
		</td>
		<td class="date-cell">
			<?php
			if ( isset( $item->meta->date ) ) {
				echo esc_html( $item->meta->date );
			}
			?>

		</td>
		<td class="watch-cell">
			<?php
			if ( isset( $item->meta->video_link ) ) {
				echo '<a data-id="' . esc_attr( $item->ID ) . '" class="drppsm-play-video btn-md"></a>';
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