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


Logger::debug( 'Template part: psm-sermon-col.php' );

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
 * Sermon Object.
 *
 * @var Sermon $item Sermon object.
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

	$date    = $meta->date();
	$passage = $meta->get_bible_passage();

	$link_text    = __( 'View ', 'drppsm' );
	$archive_link = $item->link;

	if ( $series->has_term() ) {
		$terms       = $series->terms();
		$series_term = $terms[0];

		$archive_link = get_term_link( $series_term );
		if ( ! is_wp_error( $archive_link ) ) {
			$archive_link .= '?play=' . $post_item->ID;
		} else {
			$archive_link = '';
			$link_text    = '';
		}
	}

	?>

			<li class="<?php echo esc_attr( $cols_str ); ?>">
				<div class="list-wrap">
					<div class="list-image">
					<?php if ( $src ) : ?>
					<a href="<?php echo esc_attr( $archive_link ); ?>" title="<?php echo esc_attr( $post_item->post_title ); ?>">
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
							<a href="<?php echo esc_attr( $archive_link ); ?>" title="<?php echo esc_attr( $post_item->post_title ); ?>">
								<?php echo esc_html( $link_text ); ?>
								&nbsp;
							</a>

						</div>
					</div>
				</div>
			</li>

	<?php
endforeach;
?>
		</ul>
	</div>
</div>