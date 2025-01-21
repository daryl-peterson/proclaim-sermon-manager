<?php
/**
 * Permalink singleton.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Traits\SingletonTrait;

/**
 * Permalink singleton.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class PermaLinks {

	use SingletonTrait;

	/**
	 * Permalinks array.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private static array $permalinks;

	/**
	 * Common slug.
	 *
	 * @var bool
	 * @since 1.0.0
	 */
	private static bool $common_slug;

	/**
	 * Sermons slug.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private static string $sermons;

	/**
	 * Initialize object.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		self::init_common_slug();
		self::init_permalinks();
		self::init_sermons();
	}

	/**
	 * Return permalinks array.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function get(): array {
		self::get_instance();
		return self::$permalinks;
	}

	/**
	 * Add permalink.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param string $slug     Slug name.
	 * @return string
	 * @since 1.0.0
	 */
	public static function add( string $taxonomy, string $slug ): string {
		self::get_instance();

		$prefix = '';

		if ( self::$common_slug ) {
			$prefix = self::$sermons . '/';
		}

		$slug = trim( $prefix . sanitize_title( $slug ) );
		$slug = untrailingslashit( $slug );
		$slug = strtolower( $slug );

		self::$permalinks[ $taxonomy ] = $slug;

		return $slug;
	}

	/**
	 * Get permalink.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @return string
	 * @since 1.0.0
	 */
	public static function delete( string $taxonomy ): void {
		self::get_instance();
		if ( isset( self::$permalinks[ $taxonomy ] ) ) {
			unset( self::$permalinks[ $taxonomy ] );
		}
	}

	/**
	 * Initialize sermons slug.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private static function init_sermons(): void {
		if ( ! isset( self::$sermons ) ) {
			self::$sermons = sanitize_title( Settings::get( Settings::SERMON_PLURAL ) );
		}
	}

	/**
	 * Initialize common slug.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private static function init_common_slug(): void {
		if ( ! isset( self::$common_slug ) ) {
			self::$common_slug = Settings::get( Settings::COMMON_BASE_SLUG );
		}
	}

	/**
	 * Initialize permalinks.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private static function init_permalinks(): void {
		if ( ! isset( self::$permalinks ) ) {
			self::$permalinks = array();
		}
	}
}
