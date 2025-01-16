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
 * Get archive order by.
 *
 * @param string $default_orderby Default option is not set.
 * @return string
 * @since 1.0.0
 */
function get_archive_order_by( string $default_orderby = 'date' ): string {
	return Settings::get( Settings::ARCHIVE_ORDER_BY, $default_orderby );
}

/**
 * Get archive order.
 *
 * @param string $default_order Default order.
 * @return mixed
 * @since 1.0.0
 */
function get_archive_order( string $default_order = 'DESC' ) {
	return Settings::get( Settings::ARCHIVE_ORDER, $default_order );
}

/**
 * Get a field from the taxonomy definition.
 *
 * @param mixed $taxonomy Taxonomy name.
 * @param mixed $field_name Field to get.
 * @return null|string
 * @since 1.0.0
 */
function get_taxonomy_field( $taxonomy, $field_name ): ?string {
	$tax = get_taxonomy( $taxonomy );

	if ( ! $tax instanceof WP_Taxonomy ) {
		return null;
	}

	if ( isset( $tax->$field_name ) ) {
		return $tax->$field_name;
	}

	if ( isset( $tax->labels->$field_name ) ) {
		return $tax->labels->$field_name;
	}

	return null;
}

/**
 * Get post type, taxonomy definition.
 *
 * @param string $item_name Name of the post type, taxonomy.
 * @return mixed Definition array on success.
 * @since 1.0.0
 */
function get_type_def( string $item_name ): mixed {
	$trans_key = 'drppsm_defs';
	$trans     = get_transient( $trans_key );

	if ( ! $trans ) {
		return $trans;
	}

	if ( ! key_exists( $item_name, $trans ) ) {
		return null;
	}
	return $trans[ $item_name ];
}

/**
 * Set post type, taxonomy def.
 *
 * @param string $item_name Name of the post type, taxonomy.
 * @param mixed  $item_value Definition array.
 * @return void
 * @since 1.0.0
 */
function set_type_def( string $item_name, mixed $item_value ): void {
	$trans_key = 'drppsm_defs';
	$trans     = get_transient( $trans_key );
	if ( ! is_array( $trans ) ) {
		$trans = array();
	}
	$trans[ $item_name ] = $item_value;
	set_transient( $trans_key, $trans );
	set_transient( $trans_key . '_exp', true, WEEK_IN_SECONDS );
}

function expire_type_def( int $expiration, mixed $value, string $transient ) {
	Logger::debug(
		array(
			'EXPIRATION' => $expiration,
			'VALUE'      => $value,
			'TRANSIENT'  => $transient,
		)
	);
	return $expiration;
}
add_filter( 'expiration_of_transient_drppsm_defs_exp', __NAMESPACE__ . "\\expire_type_def", 10, 3 );

/**
 * Removes all sorts of quotes from a string.
 *
 * @see   http://unicode.org/cldr/utility/confusables.jsp?a=%22&r=None
 * @param mixed $item Item to remove quotes from if it's a string.
 * @return mixed Unquoted string if string supplied, original variable otherwise.
 * @since 1.0.0
 */
function unquote( mixed $item ): mixed {
	if ( is_string( $item ) ) {
		return str_replace(
			array(
				"\x22",
				"\x27\x27",
				"\xCA\xBA",
				"\xCB\x9D",
				"\xCB\xAE",
				"\xCB\xB6",
				"\xD7\xB2",
				"\xD7\xB4",
				"\xE1\xB3\x93",
				"\xE2\x80\x9C",
				"\xE2\x80\x9D",
				"\xE2\x80\x9F",
				"\xE2\x80\xB3",
				"\xE2\x80\xB6",
				"\xE3\x80\x83",
				"\xEF\xBC\x82",
			),
			'',
			$item
		);
	} else {
		return $item;
	}
}

/**
 * Get the current page number.
 *
 * @return int
 * @since 1.0.0
 */
function get_page_number(): int {
	if ( get_query_var( 'paged' ) ) {
		$paged = get_query_var( 'paged' );
	} elseif ( get_query_var( 'page' ) ) {
		$paged = get_query_var( 'page' );
	} else {
		$paged = 1;
	}
	return $paged;
}

/**
 * Add taxonomy query vars.
 *
 * @param array $vars Current query vars.
 * @return array
 * @since 1.0.0
 */
function add_query_vars( array $vars ): array {

	$friendly = array_keys( DRPPSM_TAX_MAP );
	$vars     = array_merge( $vars, $friendly );
	return $vars;
}

add_filter( 'query_vars', __NAMESPACE__ . '\\add_query_vars' );
