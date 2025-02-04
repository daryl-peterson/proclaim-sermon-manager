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

defined( 'ABSPATH' ) || exit;

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
	 * Tax archive ttl.
	 *
	 * @since 1.0.0
	 */
	public const TAX_ARCHIVE_TTL = 8 * HOUR_IN_SECONDS;


	/**
	 * Sermon image list ttl.
	 *
	 * @since 1.0.0
	 */
	public const SERMON_IMAGE_LIST_TTL = 8 * HOUR_IN_SECONDS;

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
	 * @param string $wildcard Wildcard to match.
	 * @return void
	 * @since 1.0.0
	 */
	public static function delete( string $wildcard ) {
		global $wpdb;

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $wpdb->options WHERE option_name LIKE %s",
				$wildcard
			)
		);
	}

	/**
	 * Delete all transients.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function delete_all() {
		foreach ( DRPPSM_TAX_MAP as  $tax_name ) {
			self::delete( '%transient%' . $tax_name . '_%' );
		}

		$pt = DRPPSM_PT_SERMON . '_imagelist';
		self::delete( '%transient%' . $pt . '_%' );
	}
}
