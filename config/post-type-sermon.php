<?php
/**
 * Sermon post type configuration.
 *
 * @package Sermon Manager
 */

namespace DRPSermonManager;

$permalinks = App::init()->permalinks();
use DRPSermonManager\Constants\CAP;
use DRPSermonManager\Constants\PT;

return array(
	'labels'              => array(
		'name'                  => __( 'Sermons', 'drpsermon' ),
		'singular_name'         => __( 'Sermon', 'drpsermon' ),
		'all_items'             => __( 'Sermons', 'drpsermon' ),
		'menu_name'             => _x( 'Sermons', 'menu', 'drpsermon' ),
		'add_new'               => __( 'Add New', 'drpsermon' ),
		'add_new_item'          => __( 'Add new sermon', 'drpsermon' ),
		'edit'                  => __( 'Edit', 'drpsermon' ),
		'edit_item'             => __( 'Edit sermon', 'drpsermon' ),
		'new_item'              => __( 'New sermon', 'drpsermon' ),
		'view'                  => __( 'View sermon', 'drpsermon' ),
		'view_item'             => __( 'View sermon', 'drpsermon' ),
		'search_items'          => __( 'Search sermon', 'drpsermon' ),
		'not_found'             => __( 'No sermons found', 'drpsermon' ),
		'not_found_in_trash'    => __( 'No sermons found in trash', 'drpsermon' ),
		'featured_image'        => __( 'Sermon image', 'drpsermon' ),
		'set_featured_image'    => __( 'Set sermon image', 'drpsermon' ),
		'remove_featured_image' => __( 'Remove sermon image', 'drpsermon' ),
		'use_featured_image'    => __( 'Use as sermon image', 'drpsermon' ),
		'insert_into_item'      => __( 'Insert to sermon', 'drpsermon' ),
		'uploaded_to_this_item' => __( 'Uploaded to this sermon', 'drpsermon' ),
		'filter_items_list'     => __( 'Filter sermon', 'drpsermon' ),
		'items_list_navigation' => __( 'Sermon navigation', 'drpsermon' ),
		'items_list'            => __( 'Sermon list', 'drpsermon' ),
	),
	'public'              => true,
	'show_ui'             => true,
	'capability_type'     => PT::SERMON,
	'capabilities'        => array(
		CAP::MANAGE_CATAGORIES => CAP::MANAGE_CATAGORIES,
		CAP::MANAGE_SETTINGS   => CAP::MANAGE_SETTINGS,
	),
	'map_meta_cap'        => true,
	'publicly_queryable'  => true,
	'exclude_from_search' => false,
	'show_in_menu'        => true,
	'menu_icon'           => 'icon-drpsermon',
	'hierarchical'        => false,
	'rewrite'             => array(
		'slug'       => $permalinks[ PT::SERMON ],
		'with_front' => false,
	),
	'query_var'           => true,
	'show_in_nav_menus'   => true,
	'show_in_rest'        => true,
	'has_archive'         => true,

	'supports'            => array(
		'title',
		'thumbnail',
		'publicize',
		'wpcom-markdown',
		'comments',
		'entry-views',
		'elementor',
		'excerpt',
		'revisions',
		'author',
		// 'editor',
	),
);
