<?php
/**
 * Taxonomy preacher configuration.
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
use DRPPSM\Interfaces\OptionsInt;

$permalinks = App::init()->permalinks();
$opts       = App::init()->get( OptionsInt::class );
$slug       = Tax::PREACHER;

$label = OptGeneral::get( Settings::FIELD_PREACHER, __( 'Preacher', 'drppsm' ) );

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
		'search_items'      => wp_sprintf( __( 'Search %s', 'drppsm' ), $label ),
		/* translators: %s: All preachers */
		'all_items'         => wp_sprintf( __( 'All %s', 'drppsm' ), $label ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		/* translators: %s: Edit preacher */
		'edit_item'         => wp_sprintf( __( 'Edit %s', 'drppsm' ), $label ),
		/* translators: %s: Update preacher */
		'update_item'       => wp_sprintf( __( 'Update %s', 'drppsm' ), $label ),
		/* translators: %s: Add new preacher */
		'add_new_item'      => wp_sprintf( __( 'Add new %s', 'drppsm' ), $label ),
		/* translators: %s: New preacher */
		'new_item_name'     => wp_sprintf( __( 'New %s name', 'drppsm' ), $label ),
		/* translators: %s: No preachers found */
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
