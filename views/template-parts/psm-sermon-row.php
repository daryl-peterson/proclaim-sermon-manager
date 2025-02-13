<?php
/**
 * Sermon Image grid.
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
$required = array( 'list', 'columns', 'size' );

$result = require_once 'psm-check-args.php';
if ( ! $result ) {
	return;
}

$list     = $args['list'];
$cols     = $args['columns'];
$cols_str = 'col' . $cols;
$size     = $args['size'];

?>

<div id="drppsm-sc-wrapper">
	<div id="drppsm-image-list">
		<ul>

<?php

$fmt = get_option( 'date_format' );
$cnt = 0;

/**
 * @var Sermon $item Post object.
 */
foreach ( $list as $item ) :

	$src      = null;
	$cols_str = 'col' . $cols;

	$post_item = $item->post;
	$meta      = $item->meta;
	$preacher  = $item->preacher;
	$series    = $item->series;


	// Skip if no image.
	$src = get_sermon_image_url( $size, true, true, $post_item );
	if ( ! $src ) {
		continue;
	}

	$date      = $meta->date();
	$passage   = $meta->get_bible_passage();
	$link_text = __( 'View ', 'drppsm' );
	$link      = $item->link;

	if ( $series->has_term() ) {
		$terms = $series->terms();
		$term  = $terms[0];

		$link_tmp = get_term_link( $term );
		if ( ! is_wp_error( $link_tmp ) ) {
			$link = $link_tmp . '?play=' . $post_item->ID;
		}
	}

	?>

			<li class="row">
				<div class="sermon-image">
					<?php if ( $src ) : ?>
					<a href="<?php echo esc_attr( $link ); ?>" title="<?php echo esc_attr( $post_item->post_title ); ?>">
						<img src="<?php echo esc_attr( $src ); ?>" class="drppsm_series">
					</a>
					<?php endif; ?>
				</div>
				<div class="list-info">
					<div class="title"><?php echo esc_html( $post_item->post_title ); ?>&nbsp;</div>
					<div class="date"><?php echo esc_html( $date ); ?>&nbsp;</div>
					<div class="preacher"><?php echo esc_html( $preacher->name() ); ?>&nbsp;</div>
					<div class="passage"><?php echo esc_html( $passage ); ?>&nbsp;</div>
					<div class="archive-link">
						<a href="<?php echo esc_attr( $link ); ?>" title="<?php echo esc_attr( $post_item->post_title ); ?>">
							<?php echo esc_html( $link_text ); ?>
							&nbsp;
						</a>
					</div>

				</div>
			</li>

	<?php
endforeach;
?>
		</ul>
	</div>
</div>