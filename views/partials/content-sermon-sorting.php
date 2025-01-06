<?php
/**
 * Sermon sorting template.
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) or exit;

$sermon_filtering_args = get_item( 'sermon_filtering_args' );

if ( ! isset( $sermon_filtering_args ) ) {
	return;
}
extract( $sermon_filtering_args );

$failure      = '<p><b>' . DRPPSM_TITLE . '</b>: Partial "<i>' . str_replace( '.php', '', basename( __FILE__ ) ) . '</i>" loaded incorrectly.</p>';
$requirements = array(
	'action',
	'filters',
	'visibility_mapping',
	'args',
);
$hide_values  = array( 'yes', 'hide', 1, '1', true );


// Need to fix this !
foreach ( $requirements as $required_variable ) {
	if ( ! isset( $$required_variable ) ) {
		echo $failure;
		return;
	} else {
		Logger::debug(
			array(
				'REQUIREMENTS' => $requirements,
				'REQUIRED'     => $required_variable,
				'VALUE'        => $$required_variable,
			)
		);
	}
}

?>

<div id="drppsm-sermon-sorting">
<?
foreach ( $args['filters'] as $filter ){

}
?>
HERE
</div>