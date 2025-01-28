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

/**
 * @var \WP_Post $item_first Post object.
 */
$item_first = array_shift( $list );

?>
<div id="drppsm-sc-wrapper">

	<div id="drppsm-archive-header">
		<h2><?php echo esc_html( $item_first->post_title ); ?></h2>
	</div>

<?php


foreach ( $list as $item ) :


	?>


<?php endforeach; ?>
</div>