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

use WP_Exception;
use WP_Taxonomy;

/**
 * Get general option
 *
 * @param string $key
 * @param mixed  $default_value
 * @return mixed
 * @since 1.0.0
 */
function get_option_general( string $key, mixed $default_value = null ): mixed {
	$option_name = Helper::get_key_name( $key );
	$options     = \get_option( OptGeneral::OPTION_KEY, $default_value );
	if ( ! is_array( $options ) ) {
		return $default_value;
	}

	if ( ! key_exists( $option_name, $options ) ) {
		return $default_value;
	}
	return $options[ $option_name ];
}

/**
 * Get advanced option
 *
 * @param string $key
 * @param mixed  $default_value
 * @return mixed
 * @since 1.0.0
 */
function get_option_adv( string $key, mixed $default_value = null ): mixed {
	$option_name = Helper::get_key_name( $key );
	$options     = \get_option( OptAdvance::OPTION_KEY, $default_value );
	if ( ! is_array( $options ) ) {
		return $default_value;
	}

	if ( ! key_exists( $option_name, $options ) ) {
		return $default_value;
	}
	return $options[ $option_name ];
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
