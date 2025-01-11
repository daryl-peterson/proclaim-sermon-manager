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

$trans_key = 'drppsm_tax_series_def';
$trans     = get_transient( $trans_key );
if ( $trans ) {
	return $trans;
}

$permalinks = App::init()->permalinks();

$result = array(
	'hierarchical'      => false,
	'label'             => __( 'Series', 'drppsm' ),
	'labels'            => array(
		'name'              => __( 'Series', 'drppsm' ),
		'singular_name'     => __( 'Series', 'drppsm' ),
		'menu_name'         => _x( 'Series', 'menu', 'drppsm' ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		'search_items'      => __( 'Search series', 'drppsm' ),
		'all_items'         => __( 'All series', 'drppsm' ),
		'edit_item'         => __( 'Edit series', 'drppsm' ),
		'update_item'       => __( 'Update series', 'drppsm' ),
		'add_new_item'      => __( 'Add new series', 'drppsm' ),
		'new_item_name'     => __( 'New series name', 'drppsm' ),
		'not_found'         => __( 'No series found', 'drppsm' ),
	),
	'show_ui'           => true,
	'query_var'         => true,
	'show_in_rest'      => true,
	'show_admin_column' => true,
	'rewrite'           => array(
		'slug'       => $permalinks[ DRPPSM_TAX_SERIES ],
		'with_front' => false,
	),
	'capabilities'      => DRPPSM_TAX_CAPS,
);

set_transient( $trans_key, $result, WEEK_IN_SECONDS );
return $result;
