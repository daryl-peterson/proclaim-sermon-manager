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

defined( 'ABSPATH' ) || exit;

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
		'input'      => array(),
		'select'     => array(),

	);

	return $allowed_tags;
}
