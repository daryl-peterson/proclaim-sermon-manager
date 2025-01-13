<?php
/**
 * Sermon archive content
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

$template     = str_replace( '.php', '', basename( __FILE__ ) );
$failure      = '<p><b>' . DRPPSM_TITLE . '</b>: Partial "<i>' . esc_html( $template ) . '</i>" loaded incorrectly.</p>';
$requirements = array(
	'term_tax',
	'term_id',
	'url',
);

if ( ! isset( $args ) || ! is_array( $args ) ) {
	Logger::error( 'Args variable does not exist. Template : ' . $template );
	render_html( $failure );
	return;
}




?>

<div id="<?php echo esc_attr( $args['term_tax'] ); ?>-grid-<?php echo esc_attr( $args['term_id'] ); ?>" class="">
<image src="<?php echo esc_attr( $args['url'] ); ?>">


</div>
