<?php
/**
 * Service type taxonomy configuration.
 *
 * @package Sermon Manager
 * @return array Service type config.
 */

namespace DRPSermonManager;

use DRPSermonManager\Constants\CAP;
use DRPSermonManager\Constants\TAX;
use DRPSermonManager\Interfaces\OptionsInt;

$permalinks   = App::init()->permalinks();
$opts         = App::init()->get( OptionsInt::class );
$slug         = TAX::SERVICE_TYPE;
$capabilities = array(
	'manage_terms' => CAP::MANAGE_CATAGORIES,
	'edit_terms'   => CAP::MANAGE_CATAGORIES,
	'delete_terms' => CAP::MANAGE_CATAGORIES,
	'assign_terms' => CAP::MANAGE_CATAGORIES,
);
$label        = __( 'Service Type', 'drpsermon' );
$option_label = strtolower( $opts->get( 'service_type_label', '' ) );
if ( ! empty( $option_label ) ) {
	$label = $option_label;
}

return array(
	'hierarchical' => false,
	'label'        => ucwords( $label ),
	'labels'       => array(
		'name'              => ucwords( $label . 's' ),
		'singular_name'     => ucwords( $label ),
		'menu_name'         => ucwords( $label . 's' ),
		/* translators: %s: Search */
		'search_items'      => wp_sprintf( __( 'Search %s', 'drpsermon' ), $label ),
		/* translators: %s: All service types */
		'all_items'         => wp_sprintf( __( 'All %s', 'drpsermon' ), $label ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		/* translators: %s: Edit service type */
		'edit_item'         => wp_sprintf( __( 'Edit %s', 'drpsermon' ), $label ),
		/* translators: %s: Update service type */
		'update_item'       => wp_sprintf( __( 'Update %s', 'drpsermon' ), $label ),
		/* translators: %s: Add service type */
		'add_new_item'      => wp_sprintf( __( 'Add new %s', 'drpsermon' ), $label ),
		/* translators: %s: New service type */
		'new_item_name'     => wp_sprintf( __( 'New %s name', 'drpsermon' ), $label ),
		/* translators: %s: No service type found */
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
