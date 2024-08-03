<?php
/**
 * Bible book taxonomy configuration.
 *
 * @package Sermon Manager
 *
 * @return array Configuration array.
 */

namespace DRPSermonManager;

use DRPSermonManager\Constants\CAP;
use DRPSermonManager\Constants\TAX;

$permalinks   = PermaLinks::init()->get();
$slug         = TAX::BIBLE_BOOK;
$capabilities = array(
	'manage_terms' => CAP::MANAGE_CATAGORIES,
	'edit_terms'   => CAP::MANAGE_CATAGORIES,
	'delete_terms' => CAP::MANAGE_CATAGORIES,
	'assign_terms' => CAP::MANAGE_CATAGORIES,
);

return array(
	'hierarchical' => false,
	'label'        => __( 'Books', 'drpsermon' ),
	'labels'       => array(
		'name'              => __( 'Bible books', 'drpsermon' ),
		'singular_name'     => __( 'Book', 'drpsermon' ),
		'menu_name'         => _x( 'Books', 'menu', 'drpsermon' ),
		'search_items'      => __( 'Search books', 'drpsermon' ),
		'all_items'         => __( 'All books', 'drpsermon' ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => __( 'Edit book', 'drpsermon' ),
		'update_item'       => __( 'Update book', 'drpsermon' ),
		'add_new_item'      => __( 'Add new book', 'drpsermon' ),
		'new_item_name'     => __( 'New book name', 'drpsermon' ),
		'not_found'         => __( 'No books found', 'drpsermon' ),
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
