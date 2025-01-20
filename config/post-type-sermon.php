<?php
/**
 * Post type sermon configuration.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\Caps;

$trans_key = DRPPSM_PT_SERMON;
$trans     = get_type_def( $trans_key );
if ( $trans ) {
	return $trans;
}

$permalinks = app()->permalinks();

$capabilities = array(
	Caps::MANAGE_SETTINGS   => Caps::MANAGE_SETTINGS,
	Caps::MANAGE_CATAGORIES => Caps::MANAGE_CATAGORIES,
);

$menu_icon = Settings::get( Settings::MENU_ICON );
$single    = Settings::get( Settings::SERMON_SINGULAR );
$plural    = Settings::get( Settings::SERMON_PLURAL );

$result = array(
	'labels'              => array(
		'name'                  => $plural,
		'singular_name'         => $single,
		'all_items'             => $plural,

		/* translators: %s: sermon */
		'menu_name'             => $plural,
		'add_new'               => __( 'Add New', 'drppsm' ),

		/* translators: %s: add new sermon */
		'add_new_item'          => wp_sprintf( _x( 'Add New %s', 'drppsm' ), $single ),

		/* translators: %s: edit sermon */
		'edit_item'             => wp_sprintf( __( 'Edit %s', 'drppsm' ), $single ),

		/* translators: %s: new sermon */
		'new_item'              => wp_sprintf( __( 'New %s', 'drppsm' ), $single ),

		/* translators: %s: view sermon */
		'view'                  => wp_sprintf( __( 'View %', 'drppsm' ), $single ),

		/* translators: %s: view sermon */
		'view_item'             => wp_sprintf( __( 'View %', 'drppsm' ), $single ),

		/* translators: %s: search sermons */
		'search_items'          => wp_sprintf( _x( 'Search %s', 'Search %s', 'drppsm' ), $plural ),

		/* translators: %s: sermon not found */
		'not_found'             => wp_sprintf( _x( 'No %s found', 'No %s found', 'drppsm' ), $plural ),

		/* translators: %s: sermon not found in trash*/
		'not_found_in_trash'    => wp_sprintf( _x( 'No %s found in trash', 'No %s found in trash', 'drppsm' ), $plural ),

		/* translators: %s: set featured image */
		'featured_image'        => wp_sprintf( _x( 'Set %s Image', '%s Image', 'drppsm' ), $single ),

		/* translators: %s: featured image */
		'set_featured_image'    => wp_sprintf( __( '%s Image', 'drppsm' ), $single ),

		/* translators: %s: remove featured image */
		'remove_featured_image' => wp_sprintf( __( 'Remove %s Image', 'drppsm' ), $single ),

		'use_featured_image'    => __( 'Use as Sermon Image', 'drppsm' ),
		'insert_into_item'      => __( 'Insert to sermon', 'drppsm' ),
		'uploaded_to_this_item' => __( 'Uploaded to this sermon', 'drppsm' ),
		'filter_items_list'     => __( 'Filter Sermons', 'drppsm' ),
		'items_list_navigation' => __( 'Sermon Navigation', 'drppsm' ),
		'items_list'            => __( 'Sermon List', 'drppsm' ),
	),
	'public'              => true,
	'show_ui'             => true,
	'show_admin_column'   => true,
	'capability_type'     => DRPPSM_PT_SERMON,
	'capabilities'        => $capabilities,
	'map_meta_cap'        => true,
	'publicly_queryable'  => true,
	'exclude_from_search' => false,
	'menu_icon'           => $menu_icon,
	'hierarchical'        => false,
	'rewrite'             => array(
		'slug'       => $permalinks[ DRPPSM_PT_SERMON ],
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

set_type_def( $trans_key, $result );
return $result;
