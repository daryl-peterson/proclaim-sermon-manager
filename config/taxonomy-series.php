<?php
/**
 * Series taxonomy configuration.
 *
 * @package Sermon Manager
 *
 * @return array Series config.
 */

namespace DRPSermonManager;

use DRPSermonManager\Constants\CAP;
use DRPSermonManager\Constants\TAX;

$permalinks   = PermaLinks::init()->get();
$slug         = TAX::SERIES;
$capabilities = array(
	'manage_terms' => CAP::MANAGE_CATAGORIES,
	'edit_terms'   => CAP::MANAGE_CATAGORIES,
	'delete_terms' => CAP::MANAGE_CATAGORIES,
	'assign_terms' => CAP::MANAGE_CATAGORIES,
);

return array(
	'hierarchical' => false,
	'label'        => __( 'Series', 'drpsermon' ),
	'labels'       => array(
		'name'              => __( 'Series', 'drpsermon' ),
		'singular_name'     => __( 'Series', 'drpsermon' ),
		'menu_name'         => _x( 'Series', 'menu', 'drpsermon' ),
		'search_items'      => __( 'Search series', 'drpsermon' ),
		'all_items'         => __( 'All series', 'drpsermon' ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => __( 'Edit series', 'drpsermon' ),
		'update_item'       => __( 'Update series', 'drpsermon' ),
		'add_new_item'      => __( 'Add new series', 'drpsermon' ),
		'new_item_name'     => __( 'New series name', 'drpsermon' ),
		'not_found'         => __( 'No series found', 'drpsermon' ),
	),
	'show_ui'      => true,
	'query_var'    => true,
	'show_in_rest' => true,
	'rewrite'      => array(
		'slug'       => $permalinks[ $slug ],
		'with_front' => false,
	),
	'capabilities' => $capabilities,
);
