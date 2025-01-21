<?php
/**
 * Taxonomy topics configuration.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

$single = __( 'Topic', 'drppsm' );
$plural = __( 'Topics', 'drppsm' );
$slug   = PermaLinks::add( DRPPSM_TAX_TOPIC, $plural );

$result = array(
	'hierarchical'      => false,
	'label'             => $plural,
	'labels'            => array(
		'name'              => $plural,
		'singular_name'     => $single,
		'menu_name'         => $plural,
		'parent_item'       => null,
		'parent_item_colon' => null,
		/* translators: %s Search Topics */
		'search_items'      => wp_sprintf( _x( 'Search %s', 'Search %s', 'drppsm' ), $plural ),
		/* translators: %s All Topics */
		'all_items'         => wp_sprintf( __( 'All %s', 'drppsm' ), $plural ),
		/* translators: %s Edit Topic */
		'edit_item'         => wp_sprintf( __( 'Edit %s', 'drppsm' ), $single ),
		/* translators: %s Update Topic */
		'update_item'       => wp_sprintf( __( 'Update %s', 'drppsm' ), $single ),
		/* translators: %s Add New Topic */
		'add_new_item'      => wp_sprintf( __( 'Add New %s', 'drppsm' ), $single ),
		/* translators: %s New Topic Name */
		'new_item_name'     => wp_sprintf( __( 'New %s name', 'drppsm' ), $single ),
		/* translators: %s No Topic Found */
		'not_found'         => wp_sprintf( __( 'No %s found', 'drppsm' ), $single ),
	),
	'show_ui'           => true,
	'query_var'         => true,
	'show_in_rest'      => true,
	'show_admin_column' => true,
	'rewrite'           => array(
		'slug'       => $slug,
		'with_front' => false,
	),
	'capabilities'      => DRPPSM_TAX_CAPS,
);
return $result;
