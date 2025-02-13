<?php
/**
 * Taxonomy Image grid.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

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

$no_image_text = __( 'Coming Soon', 'drppsm' );


?>

<div id="drppsm-sc-wrapper">
	<div id="drppsm-image-list">
		<ul>

<?php

$fmt = get_option( 'date_format' );
$cnt = 0;

/**
 * @var TaxMetaData $item Object.
 */
foreach ( $list as $item ) :
	Logger::debug( array( 'Item' => $item ) );

	if ( isset( $item->term ) ) {

		/**
		* @var WP_Term $object Object.
		*/
		$object = $item->term;
	} else {
		continue;
	}

	$permalinks = PermaLinks::get();
	$test       = implode( '/', array( get_site_url(), $permalinks['drppsm_sermon'] ) ) . '/?drppsm_series=' . $object->slug;

	$no_image  = false;
	$link      = get_term_link( $object->term_id );
	$img_class = $object->taxonomy;

	$src = null;
	if ( isset( $item->image_id ) ) {
		$src = wp_get_attachment_image_url( $item->image_id, $size );
		Logger::debug( "Image found: $src" );
	}
	if ( ! $src ) {
		$tax_name = $object->taxonomy;
		$src      = DRPPSM_URL . "assets/images/coming-soon-{$taxonomy}.jpg";
	}
	$cols_str = 'col' . $cols . " $object->taxonomy";
	?>



			<li class="<?php echo esc_attr( $cols_str ); ?>">
				<div class="list-wrap">
					<div class="list-image">

					<?php if ( $src ) : ?>
					<a href="<?php echo esc_attr( $link ); ?>" title="<?php echo esc_attr( $object->name ); ?>">
						<img src="<?php echo esc_attr( $src ); ?>" class="<?php echo esc_attr( $img_class ); ?>">
					</a>
					<?php endif; ?>
					</div>
					<div class="list-info">
						<div class="title"><?php echo esc_html( $object->name ); ?>&nbsp;</div>
						<div class="date"><?php echo esc_html( format_date( absint( $item->date ) ) ); ?>&nbsp;</div>
						<div class="text-italic"><?php echo esc_html( "$object->count Messages" ); ?>&nbsp;</div>
						<div class="archive-link">
							<a href="<?php echo esc_attr( $link ); ?>" title="<?php echo esc_attr( $object->name ); ?>">
								<?php echo esc_html( 'View Archive' ); ?>
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