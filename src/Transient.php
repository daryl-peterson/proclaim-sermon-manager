<?php
/**
 * Transients.
 *
 * @package     DRPPSM\Transient
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

/**
 * Transients.
 *
 * @package     DRPPSM\Transient
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Transient {

	/**
	 * TTL 8 hours.
	 *
	 * @since 1.0.0
	 */
	public const TTL_8_HOURS = 8 * HOUR_IN_SECONDS;

	/**
	 * TTL 12 hours.
	 *
	 * @since 1.0.0
	 */
	public const TTL_12_HOURS = 12 * HOUR_IN_SECONDS;

	/**
	 * TTL 24 hours.
	 *
	 * @since 1.0.0
	 */
	public const TTL_24_HOURS = DAY_IN_SECONDS;

	/**
	 * Get transient.
	 *
	 * @param string $key Key name.
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function get( string $key ): mixed {

		$result = get_transient( $key );
		$result = maybe_unserialize( $result );

		return $result;
	}

	/**
	 * Set transient.
	 *
	 * @param string $key Key name.
	 * @param mixed  $value Value to add.
	 * @param int    $expiration Expiration time.
	 * @return bool
	 * @since 1.0.0
	 */
	public static function set( string $key, mixed $value, int $expiration = 0 ): bool {
		$value  = maybe_serialize( $value );
		$result = set_transient( $key, $value, $expiration );
		return $result;
	}

	/**
	 * Delete all transients with a wildcard.
	 *
	 * @param string $key
	 * @return bool
	 * @since 1.0.0
	 */
	public static function delete( string $key ): bool {
		return delete_transient( $key );
	}

	/**
	 * Delete all transients.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public static function delete_all(): bool {
		global $wpdb;

		$return = false;

		$patterns = array(
			'_transient_timeout_drppsm%',
			'_transient_drppsm%',
		);

		foreach ( $patterns as $pattern ) {
			$sql    = $wpdb->prepare(
				"DELETE FROM $wpdb->options WHERE option_name LIKE %s",
				$pattern
			);
			$result = $wpdb->query( $sql );
			if ( false !== $result ) {
				$return = true;
			}
		}

		wp_cache_flush();
		return $return;
	}
}
