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


	public const TAX_ARCHIVE     = 'drppsm_tax_archive';
	public const TAX_ARCHIVE_TTL = HOUR_IN_SECONDS;


	public static function key( string $prefix, mixed $args ): string {
		return esc_sql( $prefix . '_' . md5( serialize( $args ) ) );
	}

	public static function get( string $key ): mixed {

		$result = get_transient( $key );
		$result = maybe_unserialize( $result );
		Logger::debug(
			array(
				'KEY'    => $key,
				'RESULT' => $result,
			)
		);
		return $result;
	}

	public static function set( string $key, mixed $value, int $expiration = 0 ): bool {
		$value  = maybe_serialize( $value );
		$result = set_transient( $key, $value, $expiration );

		return $result;
	}
}
