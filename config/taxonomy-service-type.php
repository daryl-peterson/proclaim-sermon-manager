<?php
/**
 * Taxonomy service type configuration.
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
use DRPPSM\Interfaces\OptionsInt;

$permalinks = app()->permalinks();
$opts       = app()->get( OptionsInt::class );
$slug       = Tax::SERVICE_TYPE;

$capabilities = array(
	'manage_terms' => Caps::MANAGE_CATAGORIES,
	'edit_terms'   => Caps::MANAGE_CATAGORIES,
	'delete_terms' => Caps::MANAGE_CATAGORIES,
	'assign_terms' => Caps::MANAGE_CATAGORIES,
);
$label        = Tax::get_label( Tax::SERVICE_TYPE );

return array(
	'hierarchical' => false,
	'label'        => ucwords( $label ),
	'labels'       => array(
		'name'              => ucwords( $label . 's' ),
		'singular_name'     => ucwords( $label ),
		'menu_name'         => ucwords( $label . 's' ),
		/* translators: %s: Search */
		'search_items'      => wp_sprintf( __( 'Search %s', 'drppsm' ), $label ),
		/* translators: %s: All service types */
		'all_items'         => wp_sprintf( __( 'All %s', 'drppsm' ), $label ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		/* translators: %s: Edit service type */
		'edit_item'         => wp_sprintf( __( 'Edit %s', 'drppsm' ), $label ),
		/* translators: %s: Update service type */
		'update_item'       => wp_sprintf( __( 'Update %s', 'drppsm' ), $label ),
		/* translators: %s: Add service type */
		'add_new_item'      => wp_sprintf( __( 'Add new %s', 'drppsm' ), $label ),
		/* translators: %s: New service type */
		'new_item_name'     => wp_sprintf( __( 'New %s name', 'drppsm' ), $label ),
		/* translators: %s: No service type found */
		'not_found'         => wp_sprintf( __( 'No %s found', 'drppsm' ), $label ),
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
