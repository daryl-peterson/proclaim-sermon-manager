<?php
/**
 * Core functions.
 *
 * @package     DRPPSM\Core
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DateTime;
use ReflectionObject;
use stdClass;
use WP_Post_Type;
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
 * Get a field from the post type definition.
 *
 * @param mixed $post_type Post type name.
 * @param mixed $field_name Field to get.
 * @return null|string
 * @since 1.0.0
 */
function get_post_field( $post_type, $field_name ): ?string {
	$post = get_post_type_object( $post_type );

	if ( ! $post instanceof WP_Post_Type ) {
		return null;
	}

	if ( isset( $post->$field_name ) ) {
		return $post->$field_name;
	}

	if ( isset( $post->labels->$field_name ) ) {
		return $post->labels->$field_name;
	}

	return null;
}

/**
 * Get post type, taxonomy definition.
 *
 * @param string $item_name Name of the post type, taxonomy.
 * @return mixed Definition array on success.
 * @since 1.0.0
 *
 * @todo Fix.
 */
function get_type_def( string $item_name ): mixed {
	return null;
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
	return;
}

/**
 * Check if an array has all the keys.
 *
 * @param array $keys Keys to check.
 * @param array $array_object Array to check against.
 * @return bool
 * @since 1.0.0
 */
function has_keys( array $keys, array $array_object ): bool {
	foreach ( $keys as $key ) {
		if ( ! key_exists( $key, $array_object ) ) {
			return false;
		}
	}
	return true;
}

/**
 * Return single sermon meta key content from inside a loop.
 *
 * @param string       $meta_key The meta key name.
 * @param WP_Post|null $post     The sermon post object.
 *
 * @return mixed|null The meta key content/null if it's blank.
 * @since 1.0.0
 */
function get_sermon_meta( $meta_key = '', $post = null ): mixed {
	if ( null === $post ) {
		global $post;
	}

	if ( null === $post ) {
		return null;
	}

	$data = get_post_meta( $post->ID, $meta_key, true );
	if ( '' !== $data ) {
		return $data;
	}

	return null;
}


/**
 * Round a date to the nearest minute interval.
 *
 * @param string $date_str Date string.
 * @param string $format Date format.
 * @param int    $minute_interval Minute interval.
 * @return mixed
 * @since 1.0.0
 */
function date_round( string $date_str, string $format = 'U', $minute_interval = 10 ): string {
	$date_time = new DateTime( $date_str );

	$date_time->setTime(
		$date_time->format( 'H' ),
		round( $date_time->format( 'i' ) / $minute_interval ) * $minute_interval,
		0
	);
	return $date_time->format( $format );
}

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
	$qv_search = array(
		'page',
		'paged',
	);

	foreach ( $qv_search as $key ) {
		$var = get_query_var( $key, false );
		if ( $var ) {
			return $var;
		}
	}

	$paged = 1;
	return $paged;
}

/**
 * Filter pagination links.
 *
 * @param string $link Link to filter.
 * @return string
 * @since 1.0.0
 */
function filter_pagination( $link ) {

	$permalinks = PermaLinks::get();
	$parsed_url = wp_parse_url( $link );

	if ( ! $parsed_url ) {
		return $link;
	}

	// Check if the URL contains any of the permalinks.
	$found = false;
	foreach ( $permalinks as $permalink ) {
		if ( strpos( $parsed_url['path'], '/' . $permalink ) !== false ) {
			$found = true;
			break;
		}
	}

	// If none of the permalinks are found, return the original link.
	if ( ! $found ) {
		return $link;
	}

	// Remove the 'play' query parameter if it exists.
	$link = filter_input( INPUT_GET, 'play' ) ? remove_query_arg( 'play', $link ) : $link;

	return $link;
}

add_filter( 'paginate_links', __NAMESPACE__ . '\\filter_pagination' );

/**
 * Cast object to standard class.
 *
 * @param mixed $source_obj
 * @return stdClass
 * @throws ReflectionException
 * @since 1.0.0
 */
function cast_stdclass( $source_obj ): mixed {
	try {
		$destination = new stdClass();

		$source_reflection      = new ReflectionObject( $source_obj );
		$destination_reflection = new ReflectionObject( $destination );
		$source_props           = $source_reflection->getProperties();
		foreach ( $source_props as $source_prop ) {
			$source_prop->setAccessible( true );
			$name  = $source_prop->getName();
			$value = $source_prop->getValue( $source_obj );
			if ( $destination_reflection->hasProperty( $name ) ) {
				$destination_prop = $destination_reflection->getProperty( $name );
				$destination_prop->setAccessible( true );
				$destination_prop->setValue( $destination, $value );
			} else {
				$destination->$name = $value;
			}
		}

		return $destination;

	} catch ( \Throwable $th ) {
		Logger::error(
			array(
				'ERROR' => $th->getMessage(),
				'TRACE' => $th->getTrace(),
			)
		);
		return $source_obj;
	}
}
