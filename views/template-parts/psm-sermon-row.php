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

	$src      = null;
	$preacher = null;
	$date     = null;

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

	/**
	 * @var ?WP_Term $item->drppsm_preacher
	 */
	if ( isset( $item->drppsm_preacher ) ) {
		$preacher = $item->drppsm_preacher;
	}

	$link      = get_permalink( $item );
	$link_text = __( 'View ', 'drppsm' );
	?>



			<li class="row">

					<div class="sermon-image">
						<?php if ( $src ) : ?>
						<a href="<?php echo esc_attr( $link ); ?>" title="<?php echo esc_attr( $item->post_title ); ?>">
							<img src="<?php echo esc_attr( $src ); ?>" class="drppsm_series">
						</a>
						<?php endif; ?>
					</div>
					<div class="list-info">
						<h4><?php echo esc_html( $item->post_title ); ?></h4>

						<?php if ( $preacher ) : ?>
							<h5><?php echo esc_html( $preacher->name ); ?></h5>
						<?php endif; ?>

						<?php if ( $date ) : ?>
							<h5><?php echo esc_html( $date ); ?></h5>
						<?php endif; ?>

						<?php if ( $link ) : ?>
						<p class="archive-link">
							<a href="<?php echo esc_attr( $link ); ?>" title="<?php echo esc_attr( $item->post_title ); ?>">
								<?php echo esc_html( $link_text ); ?>
							</a>
						</p>
						<?php endif; ?>
					</div>

			</li>

	<?php
endforeach;
?>
		</ul>
	</div>
</div>