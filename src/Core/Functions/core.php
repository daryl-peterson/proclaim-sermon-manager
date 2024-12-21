<?php
/**
 * Core functions
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use WP_Taxonomy;

/**
 * Fix permalink options
 *
 * @param string $slug Name of slug.
 * @param string $default_value Default value if not found.
 * @return string
 * @since 1.0.0
 */
function fix_permalink( string $slug, string $default_value = '' ): string {
	$value = OptGeneral::get( $slug, $default_value );
	$value = trim( sanitize_title( $value ) );
	return untrailingslashit( $value );
}

function taxonomy_field( $taxonomy, $field_name ) {
	$taxonomy = get_taxonomy( $taxonomy );

	if ( ! $taxonomy instanceof WP_Taxonomy ) {
		return null;
	}

	if ( isset( $taxonomy->$field_name ) ) {
		return $taxonomy->$field_name;
	}

	if ( isset( $taxonomy->labels->$field_name ) ) {
		return $taxonomy->labels->$field_name;
	}

	return null;
}
