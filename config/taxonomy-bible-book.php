<?php
/**
 * Bible book configuration.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

$trans_key = DRPPSM_TAX_BOOK;
$trans     = get_type_def( $trans_key );
if ( $trans ) {
	return $trans;
}

$single = __( 'Book', 'drppsm' );
$plural = __( 'Books', 'drppsm' );
$slug   = PermaLinks::add( DRPPSM_TAX_BOOK, $plural );

$result = array(
	'hierarchical'      => false,
	'label'             => $plural,
	'labels'            => array(
		'name'              => $plural,
		'singular_name'     => $single,
		'menu_name'         => $plural,
		'parent_item'       => null,
		'parent_item_colon' => null,

		/* translators: %s: search */
		'search_items'      => wp_sprintf( __( 'Search %s', 'drppsm' ), $plural ),
		/* translators: %s: all */
		'all_items'         => wp_sprintf( __( 'All %s', 'drppsm' ), $plural ),
		/* translators: %s: edit */
		'edit_item'         => wp_sprintf( __( 'Edit %s', 'drppsm' ), $single ),
		/* translators: %s: update */
		'update_item'       => wp_sprintf( __( 'Update %s', 'drppsm' ), $single ),
		/* translators: %s: add new */
		'add_new_item'      => wp_sprintf( __( 'Add New %s', 'drppsm' ), $single ),
		/* translators: %s: new name */
		'new_item_name'     => wp_sprintf( __( 'New %s name', 'drppsm' ), $single ),
		/* translators: %s: not found */
		'not_found'         => wp_sprintf( __( 'No %s found', 'drppsm' ), $plural ),
	),
	'show_ui'           => true,
	'query_var'         => true,
	'show_in_rest'      => true,
	'show_admin_column' => true,
	'rewrite'           => array(
		'slug'       => $slug,
		'with_front' => false,
	),
	'capabilities'      => DRPPSM_TAX_CAPS,
);

set_type_def( $trans_key, $result );
return $result;
