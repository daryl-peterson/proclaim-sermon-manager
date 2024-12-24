<?php
/**
 * Taxonomy topics configuration.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\Caps;

$permalinks   = App::init()->permalinks();
$slug         = Tax::TOPICS;
$capabilities = array(
	'manage_terms' => Caps::MANAGE_CATAGORIES,
	'edit_terms'   => Caps::MANAGE_CATAGORIES,
	'delete_terms' => Caps::MANAGE_CATAGORIES,
	'assign_terms' => Caps::MANAGE_CATAGORIES,
);

return array(
	'hierarchical' => false,
	'label'        => __( 'Topics', 'drppsm' ),
	'labels'       => array(
		'name'              => __( 'Topics', 'drppsm' ),
		'singular_name'     => __( 'Topic', 'drppsm' ),
		'menu_name'         => _x( 'Topics', 'menu', 'drppsm' ),
		'search_items'      => __( 'Search topics', 'drppsm' ),
		'all_items'         => __( 'All topics', 'drppsm' ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => __( 'Edit topic', 'drppsm' ),
		'update_item'       => __( 'Update topic', 'drppsm' ),
		'add_new_item'      => __( 'Add new topic', 'drppsm' ),
		'new_item_name'     => __( 'New topic name', 'drppsm' ),
		'not_found'         => __( 'No topics found', 'drppsm' ),
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
