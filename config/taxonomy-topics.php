<?php
/**
 * Topics taxonomy configuration.
 *
 * @package Sermon Manager
 *
 * @return array Taxonomy config.
 */

namespace DRPSermonManager;

use DRPSermonManager\Constants\CAP;
use DRPSermonManager\Constants\TAX;

$permalinks   = PermaLinks::init()->get();
$slug         = TAX::TOPICS;
$capabilities = array(
	'manage_terms' => CAP::MANAGE_CATAGORIES,
	'edit_terms'   => CAP::MANAGE_CATAGORIES,
	'delete_terms' => CAP::MANAGE_CATAGORIES,
	'assign_terms' => CAP::MANAGE_CATAGORIES,
);

return array(
	'hierarchical' => false,
	'label'        => __( 'Topics', 'drpsermon' ),
	'labels'       => array(
		'name'              => __( 'Topics', 'drpsermon' ),
		'singular_name'     => __( 'Topic', 'drpsermon' ),
		'menu_name'         => _x( 'Topics', 'menu', 'drpsermon' ),
		'search_items'      => __( 'Search topics', 'drpsermon' ),
		'all_items'         => __( 'All topics', 'drpsermon' ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => __( 'Edit topic', 'drpsermon' ),
		'update_item'       => __( 'Update topic', 'drpsermon' ),
		'add_new_item'      => __( 'Add new topic', 'drpsermon' ),
		'new_item_name'     => __( 'New topic name', 'drpsermon' ),
		'not_found'         => __( 'No topics found', 'drpsermon' ),
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
