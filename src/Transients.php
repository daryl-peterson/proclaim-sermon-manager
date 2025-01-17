<?php
/**
 * Transients.
 *
 * @package     DRPPSM\Transients
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

/**
 * Transients.
 *
 * @package     DRPPSM\Transients
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Transients {

	/**
	 * Type definitions post, taxonomy.
	 *
	 * @since 1.0.0
	 */
	public const TYPE_DEF = 'drppsm_defs';

	/**
	 * Term optiion list. Use in cmb select options.
	 *
	 * @since 1.0.0
	 */
	public const TERM_OPTS = 'drppsm_term_opts';

	/**
	 * Term count.
	 *
	 * @since 1.0.0
	 */
	public const TERM_COUNT = 'drppsm_term_count';

	/**
	 * Term with images query.
	 *
	 * @since 1.0.0
	 */
	public const TERMS_WITH_IMAGES = 'drppsm_term_with_images';

	/**
	 * Series info.
	 *
	 * @since 1.0.0
	 */
	public const SERIES_INFO = 'drppsm_series_info';


	/**
	 * Expired key mapping.
	 *
	 * @since 1.0.0
	 */
	private const EXPIRES = array(
		self::SERIES_INFO       => self::SERIES_INFO . '_exp',
		self::TERM_COUNT        => self::TERM_COUNT . '_exp',
		self::TERM_OPTS         => self::TERM_OPTS . '_exp',
		self::TERMS_WITH_IMAGES => self::TERMS_WITH_IMAGES . '_exp',
		self::TYPE_DEF          => self::TYPE_DEF . '_exp',
	);

	/**
	 * Transient expire times mapping.
	 *
	 * @since 1.0.0
	 */
	private const TIMES = array(
		self::SERIES_INFO       => HOUR_IN_SECONDS,
		self::TERM_COUNT        => HOUR_IN_SECONDS,
		self::TERM_OPTS         => DAY_IN_SECONDS,
		self::TERMS_WITH_IMAGES => DAY_IN_SECONDS,
		self::TYPE_DEF          => WEEK_IN_SECONDS,
	);


	/**
	 * Get transient values.
	 *
	 * @param string     $key Primary transient key.
	 * @param string     $sub_key Sub key.
	 * @param mixed|null $default_value Return value if not found.
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function get( string $key, string $sub_key = '', mixed $default_value = null ): mixed {
		$expire_key = self::get_expire( $key );

		// Check if we should make sure it's expired.
		if ( $expire_key ) {
			$valid = get_transient( $expire_key );
			if ( ! $valid ) {
				delete_transient( $key );
				return $default_value;
			}
		}

		$options = get_transient( $key );
		if ( ! $options ) {
			return $default_value;
		}

		if ( '' === $sub_key ) {
			return $options;
		}

		if ( is_array( $options ) && key_exists( $sub_key, $options ) ) {
			return $options[ $sub_key ];
		}

		return $options;
	}

	/**
	 * Set transient value and possible expire timer.
	 *
	 * @param string $key Transient key.
	 * @param mixed  $value Transient value.
	 * @return void
	 * @since 1.0.0
	 */
	public static function set( string $key, mixed $value ): void {
		$expire_key  = self::get_expire( $key );
		$expire_time = self::get_time( $key );

		set_transient( $key, $value );
		if ( $expire_key ) {
			set_transient( $expire_key, true, $expire_time );
		}
	}

	/**
	 * Delete transient.
	 *
	 * @param string $key Transient key.
	 * @return void
	 * @since 1.0.0
	 */
	public static function delete( string $key ): void {
		$expire_key = self::get_expire( $key );
		delete_transient( $key );
		if ( $expire_key ) {
			delete_transient( $expire_key );
		}
	}

	public static function delete_all() {
		$keys = array_keys( self::EXPIRES );
		foreach ( $keys as $key_name ) {
			self::delete( $key_name );
		}
	}

	/**
	 * Get expire time from map.
	 *
	 * @param string $key Transient key.
	 * @return mixed
	 * @since 1.0.0
	 */
	private static function get_expire( string $key ): mixed {
		if ( ! key_exists( $key, self::EXPIRES ) ) {
			return false;
		}

		return self::EXPIRES[ $key ];
	}

	/**
	 * Get expire time.
	 *
	 * @param string $key Transient key.
	 * @return mixed
	 * @since 1.0.0
	 */
	private static function get_time( string $key ) {
		if ( ! key_exists( $key, self::TIMES ) ) {
			return 0;
		}

		return self::TIMES[ $key ];
	}
}
