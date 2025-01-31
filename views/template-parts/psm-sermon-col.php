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

defined( 'ABSPATH' ) || exit;
use WP_Post;

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
 * @var WP_Post $item Post object.
 */
foreach ( $list as $item ) :
	Logger::debug( array( 'item' => $item ) );
	$src      = null;
	$preacher = ' ';
	$date     = ' ';
	$passage  = ' ';

	$cols_str = 'col' . $cols;

	// Skip if no image.
	$src = get_sermon_image_url( $size, true, true, $item );
	if ( ! $src ) {
		continue;
	}

	// Get date.
	if ( isset( $item->meta->date ) ) {
		$date = format_date( absint( $item->meta->date ) );
	} else {
		$date = format_date( strtotime( $item->post_date ) );
	}

	if ( isset( $item->meta->bible_passage ) ) {
		$passage = $item->meta->bible_passage;
	}

	/**
	 * @var ?WP_Term $item->drppsm_preacher
	 */
	if ( isset( $item->drppsm_preacher ) ) {
		$preacher = $item->drppsm_preacher->name;
	}


	$link_text = __( 'View ', 'drppsm' );

	$link = get_permalink( $item );
	if ( ! $link ) {
		$link      = ' ';
		$link_text = ' ';
	}

	?>



			<li class="<?php echo esc_attr( $cols_str ); ?>">

				<?php if ( $src ) : ?>
				<a href="<?php echo esc_attr( $link ); ?>" title="<?php echo esc_attr( $item->post_title ); ?>">
					<img src="<?php echo esc_attr( $src ); ?>" class="drppsm_series">
				</a>
				<?php endif; ?>
				<div class="list-info">
					<div class="title"><?php echo esc_html( $item->post_title ); ?>&nbsp;</div>
					<div class="date"><?php echo esc_html( $date ); ?>&nbsp;</div>
					<div class="preacher"><?php echo esc_html( $preacher ); ?>&nbsp;</div>
					<div class="passage"><?php echo esc_html( $passage ); ?>&nbsp;</div>
					<div class="archive-link">
						<a href="<?php echo esc_attr( $link ); ?>" title="<?php echo esc_attr( $item->post_title ); ?>">
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