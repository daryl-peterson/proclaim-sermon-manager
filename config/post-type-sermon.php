<?php
/**
 * Post type sermon configuration.
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

$timer     = Timer::get_instance();
$timer_key = $timer->start( '', __FILE__ );
$trans_key = 'drppsm_sermon_post_def';
$trans     = get_transient( $trans_key );
if ( $trans ) {
	$timer->stop( $timer_key );
	return $trans;
}

$permalinks = app()->permalinks();

$capabilities = array(
	Caps::MANAGE_SETTINGS   => Caps::MANAGE_SETTINGS,
	Caps::MANAGE_CATAGORIES => Caps::MANAGE_CATAGORIES,
);

$menu_icon = Settings::get( Settings::MENU_ICON );

/* translators: sermon: sermon singular*/
$single = __( 'Sermon', 'drppsm' );

/* translators: sermon plural */
$plural = __( 'Sermons', 'drppsm' );

$result = array(
	'labels'              => array(
		'name'                  => __( 'Proclaim Sermons', 'drppsm' ),
		'singular_name'         => $single,
		'all_items'             => __( 'Sermons', 'drppsm' ),
		'menu_name'             => _x( 'Sermons', 'menu_name', 'drppsm' ),
		'add_new'               => __( 'Add New', 'drppsm' ),

		/* translators: Add New %s: Add New %s */
		'add_new_item'          => wp_sprintf( _x( 'Add New %s', 'drppsm' ), $single ),

		/* translators: Edit %s: Edit %s */
		'edit_item'             => wp_sprintf( __( 'Edit %s', 'drppsm' ), $single ),
		'new_item'              => __( 'New Sermon', 'drppsm' ),
		'view'                  => __( 'View Sermon', 'drppsm' ),
		'view_item'             => __( 'View Sermon', 'drppsm' ),

		/* translators: Search %s: Search %s */
		'search_items'          => wp_sprintf( _x( 'Search %s', 'Search %s', 'drppsm' ), $plural ),
		/* translators: No %s found: No %s found */
		'not_found'             => wp_sprintf( _x( 'No %s found', 'No %s found', 'drppsm' ), $plural ),
		/* translators: No %s found in trash: No %s found in trash */
		'not_found_in_trash'    => wp_sprintf( _x( 'No %s found in trash', 'No %s found in trash', 'drppsm' ), $plural ),

		// 'featured_image'        => __( 'Sermon Image', 'drppsm' ),

		'featured_image'        => wp_sprintf( _x( '%s Image', '%s Image', 'drppsm' ), $single ),
		'set_featured_image'    => __( 'Set Sermon Image', 'drppsm' ),
		'remove_featured_image' => __( 'Remove Sermon Image', 'drppsm' ),
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

set_transient( $trans_key, $result, WEEK_IN_SECONDS );
$timer->stop( $timer_key );
return $result;
