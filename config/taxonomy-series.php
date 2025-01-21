<?php
/**
 * Taxonomy series configuration.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

$trans_key = DRPPSM_TAX_SERIES;
$trans     = get_type_def( $trans_key );
if ( $trans ) {
	return $trans;
}

$single = Settings::get( Settings::SERIES_SINGULAR );
$plural = Settings::get( Settings::SERIES_PLURAL );
$slug   = PermaLinks::add( DRPPSM_TAX_SERIES, $plural );


$result = array(
	'hierarchical'      => false,
	'label'             => __( 'Series', 'drppsm' ),
	'labels'            => array(
		'name'              => $plural,
		'singular_name'     => $single,
		'menu_name'         => $plural,
		'parent_item'       => null,
		'parent_item_colon' => null,
		/* translators: %s Search Series */
		'search_items'      => wp_sprintf( __( 'Search %s', 'Search %s', 'drppsm' ), $plural ),
		/* translators: %s All Series */
		'all_items'         => wp_sprintf( __( 'All %s', 'drppsm' ), $plural ),
		/* translators: %s Edit Series */
		'edit_item'         => wp_sprintf( __( 'Edit %s', 'drppsm' ), $single ),
		/* translators: %s Update Series */
		'update_item'       => wp_sprintf( __( 'Update %s', 'drppsm' ), $single ),
		/* translators: %s Add New Series */
		'add_new_item'      => wp_sprintf( __( 'Add New %s', 'drppsm' ), $single ),
		/* translators: %s New Series name */
		'new_item_name'     => wp_sprintf( __( 'New %s name', 'drppsm' ), $single ),
		/* translators: %s No Series found */
		'not_found'         => wp_sprintf( __( 'No %s found', 'drppsm' ), $single ),
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
