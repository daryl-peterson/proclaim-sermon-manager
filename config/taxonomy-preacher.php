<?php
/**
 * Taxonomy preacher configuration.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

$trans_key = DRPPSM_TAX_PREACHER;
$trans     = get_type_def( $trans_key );
if ( $trans ) {
	return $trans;
}

$single = Settings::get( Settings::PREACHER_SINGULAR );
$plural = Settings::get( Settings::PREACHER_PLURAL );
$slug   = PermaLinks::add( DRPPSM_TAX_PREACHER, $plural );

$result = array(
	'hierarchical'      => false,
	'label'             => $single,
	'labels'            => array(
		'name'              => $plural,
		'singular_name'     => $single,
		'menu_name'         => $plural,
		'parent_item'       => null,
		'parent_item_colon' => null,

		/* translators: %s preacher */
		'search_items'      => wp_sprintf( _x( 'Search %s', 'Search %s', 'drppsm' ), $plural ),
		/* translators: %s preacher */
		'all_items'         => wp_sprintf( __( 'All %s', 'drppsm' ), $plural ),
		/* translators: %s preacher */
		'edit_item'         => wp_sprintf( __( 'Edit %s', 'drppsm' ), $single ),
		/* translators: %s preacher */
		'update_item'       => wp_sprintf( __( 'Update %s', 'drppsm' ), $single ),
		/* translators: %s preacher */
		'add_new_item'      => wp_sprintf( __( 'Add New %s', 'drppsm' ), $single ),
		/* translators: %s preacher */
		'new_item_name'     => wp_sprintf( __( 'New %s name', 'drppsm' ), $single ),
		/* translators: %s preacher */
		'not_found'         => wp_sprintf( __( 'No %s found', 'drppsm' ), $single ),
	),
	'show_ui'           => true,
	'query_var'         => true,
	'show_in_rest'      => true,
	'show_in_menu'      => true,
	'show_admin_column' => true,
	'rewrite'           => array(
		'slug'       => $slug,
		'with_front' => false,
	),
	'capabilities'      => DRPPSM_TAX_CAPS,
);
set_type_def( $trans_key, $result );
return $result;
