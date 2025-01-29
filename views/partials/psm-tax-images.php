<?php
/**
 * Image grid.
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

if ( ! has_keys( array( 'list', 'columns', 'size' ), $args ) ) {
	render_html( $failure );
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
 * @var stdClass $item Object.
 */
foreach ( $list as $item ) :

	$object = $item->object;
	$link   = get_term_link( $object->term_id );
	$src    = null;
	if ( isset( $item->image_id ) ) {
		$src = wp_get_attachment_image_url( $item->image_id, $size );
	}
	$cols_str = 'col' . $cols . " $object->taxonomy";
	?>



			<li class="<?php echo esc_attr( $cols_str ); ?>">
				<div>
					<?php if ( $src ) : ?>
					<a href="<?php echo esc_attr( $link ); ?>" title="<?php echo esc_attr( $object->name ); ?>">
						<img src="<?php echo esc_attr( $src ); ?>" class="<?php echo esc_attr( $object->taxonomy ); ?>">
					</a>
					<?php endif; ?>
					<div class="list-info">
						<h4><?php echo esc_html( $object->name ); ?></h4>
						<h5><?php echo esc_html( wp_date( $fmt, $item->date ) ); ?></h5>
						<p><?php echo esc_html( "$object->count Messages" ); ?></p>
						<p class="archive-link">
							<a href="<?php echo esc_attr( $link ); ?>" title="<?php echo esc_attr( $object->name ); ?>">
								<?php echo esc_html( 'View Archive' ); ?>
							</a>
						</p>
					</div>
				</div>
			</li>

	<?php
endforeach;
?>
		</ul>
	</div>
</div>