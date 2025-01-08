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

function get_archive_order_by( string $default = 'date' ): string {
	return Settings::get( Settings::ARCHIVE_ORDER_BY, 'date' );
}

function get_archive_order( string $default = 'DESC' ) {
	return Settings::get( Settings::ARCHIVE_ORDER, $default );
}

function get_taxonomy_field( $taxonomy, $field_name ) {
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

/**
 * Removes all sorts of quotes from a string.
 *
 * @see   http://unicode.org/cldr/utility/confusables.jsp?a=%22&r=None
 * @param string $string String to unquote.
 * @return mixed Unquoted string if string supplied, original variable otherwise.
 * @since 1.0.0
 */
function unquote( mixed $string ): mixed {
	if ( ! is_string( $string ) ) {
		return $string;
	}

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
		$string
	);
}
