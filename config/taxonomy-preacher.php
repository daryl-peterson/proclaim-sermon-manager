<?php
/**
 * Configuration for preacher taxonomy.
 *
 * @package Sermon Manager
 *
 * @return array Configuration array.
 */

namespace DRPSermonManager;

use DRPSermonManager\Constants\Caps;
use DRPSermonManager\Constants\Tax;
use DRPSermonManager\Interfaces\OptionsInt;

$permalinks = App::init()->permalinks();
$opts       = App::init()->get( OptionsInt::class );
$slug       = Tax::PREACHER;
$label      = $opts->get( 'preacher_label', false ) ?
		strtolower( $opts->get( 'preacher_label' ) ) : __( 'Preacher', 'drpsermon' );

$capabilities = array(
	'manage_terms' => Caps::MANAGE_CATAGORIES,
	'edit_terms'   => Caps::MANAGE_CATAGORIES,
	'delete_terms' => Caps::MANAGE_CATAGORIES,
	'assign_terms' => Caps::MANAGE_CATAGORIES,
);

return array(
	'hierarchical' => false,
	'label'        => ucwords( $label ),
	'labels'       => array(
		'name'              => ucwords( $label . 's' ),
		'singular_name'     => ucwords( $label ),
		'menu_name'         => ucwords( $label . 's' ),
		/* translators: %s: Search by preacher */
		'search_items'      => wp_sprintf( __( 'Search %s', 'drpsermon' ), $label ),
		/* translators: %s: All preachers */
		'all_items'         => wp_sprintf( __( 'All %s', 'drpsermon' ), $label ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		/* translators: %s: Edit preacher */
		'edit_item'         => wp_sprintf( __( 'Edit %s', 'drpsermon' ), $label ),
		/* translators: %s: Update preacher */
		'update_item'       => wp_sprintf( __( 'Update %s', 'drpsermon' ), $label ),
		/* translators: %s: Add new preacher */
		'add_new_item'      => wp_sprintf( __( 'Add new %s', 'drpsermon' ), $label ),
		/* translators: %s: New preacher */
		'new_item_name'     => wp_sprintf( __( 'New %s name', 'drpsermon' ), $label ),
		/* translators: %s: No preachers found */
		'not_found'         => wp_sprintf( __( 'No %s found', 'drpsermon' ), $label ),
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
