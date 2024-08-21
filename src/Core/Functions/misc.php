<?php
/**
 * Plugin functions.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DateTimeZone;

/**
 * Get app instance.
 *
 * @return App
 * @since 1.0.0
 */
function app(): App {
	return App::init();
}

/**
 * Get app object from container.
 *
 * @param string $item Key for container.
 * @return mixed
 * @since 1.0.0
 */
function app_get( string $item ): mixed {
	return App::init()->get( $item );
}

/**
 * Get setting.
 *
 * @param string $key Setting name.
 * @param mixed  $default_value Value to use if it doesn't exist.
 * @return mixed
 * @since 1.0.0
 */
function get_setting( string $key, mixed $default_value = null ): mixed {
	return app()->get_setting( $key, $default_value );
}

/**
 * Create a key for options ect.. with the KEY_PREFIX.
 *
 * @param string $name Name of key to create.
 * @param string $delimiter Delimiter to use for key.
 * @return string Key name.
 * @since 1.0.0
 */
function get_key_name( string $name, string $delimiter = '_' ): string {
	$prefix = KEY_PREFIX;

	$name = trim( trim( $name, '-_' ) );

	$len = strlen( $prefix . $delimiter );
	if ( strlen( $name ) > $len ) {
		if ( substr( $name, 0, $len ) === $prefix . $delimiter ) {
			return $name;
		}
	}

	return strtolower( $prefix . $delimiter . $name );
}

/**
 * Fix slug. Sanitize and trime.
 *
 * @param string $slug Name of slug.
 * @return string Sanitized string.
 * @since 1.0.0
 */
function fix_slug( string $slug ): string {
	return trim( sanitize_title( $slug ) );
}

/**
 * Get slug from settings and remove any trailing slashes.
 *
 * @param string $slug Name of slug.
 * @param string $default_value Default value if not found.
 * @return string
 * @since 1.0.0
 */
function get_slug( string $slug, string $default_value = '' ): string {
	return untrailingslashit( fix_slug( get_setting( $slug, $default_value ) ) );
}

/**
 * Get date formated with microseconds.
 * - Change from wp_date, it will not format microseconds.
 *
 * @param string                  $format Date format string.
 * @param integer|float|null|null $timestamp Time stamp to use.
 * @param null|DateTimeZone       $timezone Timezone to use.
 * @return string Get formated dated string.
 * @since 1.0.0
 */
function get_date( string $format, int|float|null $timestamp = null, null|DateTimeZone $timezone = null ): string {
	global $wp_locale;

	if ( null === $timestamp ) {
		$timestamp = time();
	} elseif ( ! is_numeric( $timestamp ) && ! is_float( $timestamp ) ) {
		return false;
	}

	if ( ! $timezone ) {
		$timezone = wp_timezone();
	}

	$datetime = date_create( '@' . $timestamp );
	$datetime->setTimezone( $timezone );

	if ( empty( $wp_locale->month ) || empty( $wp_locale->weekday ) ) {
		$date = $datetime->format( $format );
	} else {
		// We need to unpack shorthand `r` format because it has parts that might be localized.
		$format = preg_replace( '/(?<!\\\\)r/', DATE_RFC2822, $format );

		$new_format    = '';
		$format_length = strlen( $format );
		$month         = $wp_locale->get_month( $datetime->format( 'm' ) );
		$weekday       = $wp_locale->get_weekday( $datetime->format( 'w' ) );

		for ( $i = 0; $i < $format_length; $i++ ) {
			switch ( $format[ $i ] ) {
				case 'D':
					$new_format .= addcslashes( $wp_locale->get_weekday_abbrev( $weekday ), '\\A..Za..z' );
					break;
				case 'F':
					$new_format .= addcslashes( $month, '\\A..Za..z' );
					break;
				case 'l':
					$new_format .= addcslashes( $weekday, '\\A..Za..z' );
					break;
				case 'M':
					$new_format .= addcslashes( $wp_locale->get_month_abbrev( $month ), '\\A..Za..z' );
					break;
				case 'a':
					$new_format .= addcslashes( $wp_locale->get_meridiem( $datetime->format( 'a' ) ), '\\A..Za..z' );
					break;
				case 'A':
					$new_format .= addcslashes( $wp_locale->get_meridiem( $datetime->format( 'A' ) ), '\\A..Za..z' );
					break;
				case '\\':
					$new_format .= $format[ $i ];

					// If character follows a slash, we add it without translating.
					if ( $i < $format_length ) {
						$new_format .= $format[ ++$i ];
					}
					break;
				default:
					$new_format .= $format[ $i ];
					break;
			}
		}

		$date = $datetime->format( $new_format );
		$date = wp_maybe_decline_date( $date, $format );
	}
	return $date;
}

/**
 * Allowed html for escaping.
 * - wp_kses
 *
 * @return array
 * @since 1.0.0
 */
function allowed_html(): array {

	$allowed_tags = array(
		'a'          => array(
			'class' => array(),
			'href'  => array(),
			'rel'   => array(),
			'title' => array(),
		),
		'abbr'       => array(
			'title' => array(),
		),
		'b'          => array(),
		'blockquote' => array(
			'cite' => array(),
		),
		'cite'       => array(
			'title' => array(),
		),
		'code'       => array(),
		'del'        => array(
			'datetime' => array(),
			'title'    => array(),
		),
		'dd'         => array(),
		'div'        => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		'dl'         => array(),
		'dt'         => array(),
		'em'         => array(),
		'h1'         => array(),
		'h2'         => array(),
		'h3'         => array(),
		'h4'         => array(),
		'h5'         => array(),
		'h6'         => array(),
		'i'          => array(),
		'img'        => array(
			'alt'    => array(),
			'class'  => array(),
			'height' => array(),
			'src'    => array(),
			'width'  => array(),
		),
		'li'         => array(
			'class' => array(),
		),
		'ol'         => array(
			'class' => array(),
		),
		'p'          => array(
			'class' => array(),
		),
		'q'          => array(
			'cite'  => array(),
			'title' => array(),
		),
		'span'       => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		'strike'     => array(),
		'strong'     => array(),
		'ul'         => array(
			'class' => array(),
		),
	);

	return $allowed_tags;
}
