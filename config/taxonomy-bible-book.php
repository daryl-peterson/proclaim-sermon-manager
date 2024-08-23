<?php
/**
 * Bible book configuration.
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
use DRPPSM\Constants\Tax;

$permalinks   = App::init()->permalinks();
$slug         = Tax::BIBLE_BOOK;
$capabilities = array(
	'manage_terms' => Caps::MANAGE_CATAGORIES,
	'edit_terms'   => Caps::MANAGE_CATAGORIES,
	'delete_terms' => Caps::MANAGE_CATAGORIES,
	'assign_terms' => Caps::MANAGE_CATAGORIES,
);

return array(
	'hierarchical' => false,
	'label'        => __( 'Books', 'drppsm' ),
	'labels'       => array(
		'name'              => __( 'Bible books', 'drppsm' ),
		'singular_name'     => __( 'Book', 'drppsm' ),
		'menu_name'         => _x( 'Books', 'menu', 'drppsm' ),
		'search_items'      => __( 'Search books', 'drppsm' ),
		'all_items'         => __( 'All books', 'drppsm' ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => __( 'Edit book', 'drppsm' ),
		'update_item'       => __( 'Update book', 'drppsm' ),
		'add_new_item'      => __( 'Add new book', 'drppsm' ),
		'new_item_name'     => __( 'New book name', 'drppsm' ),
		'not_found'         => __( 'No books found', 'drppsm' ),
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
