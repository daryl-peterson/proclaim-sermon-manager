<?php
/**
 * Create extra data for sermons, series, topics, bible.
 *
 * @package     DRPPSM\SchedulerJobs
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Create extra data for sermons, series, topics, bible.
 *
 * @package     DRPPSM\SchedulerJobs
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SchedulerJobs implements Executable, Registrable {

	use SingletonTrait;

	/**
	 * Jobs queue.
	 *
	 * @var null|array
	 * @since 1.0.0
	 */
	private static null|array $jobs = null;

	/**
	 * Flag to prevent multiple hook registrations.
	 *
	 * @var bool
	 * @since 1.0.0
	 */
	private static bool $init;

	/**
	 * Initialize object properties.
	 *
	 * @return void
	 */
	protected function __construct() {
		if ( ! isset( self::$jobs ) ) {
			self::$jobs = array();
			$jobs       = get_option( Options::KEY_JOBS );

			if ( is_array( $jobs ) ) {
				self::$jobs = $jobs;
			}
		}

		self::$init = false;
	}

	/**
	 * Get object instance and register hooks.
	 *
	 * @return self
	 * @since 1.0.0
	 */
	public static function exec(): self {
		$obj = self::get_instance();
		$obj->register();
		return $obj;
	}

	/**
	 * Register hooks.
	 *
	 * @return null|bool Returns null if hooks are already registered.
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		if ( self::$init ) {
			return null;
		}

		self::$init = true;
		add_action( 'shutdown', array( $this, 'save' ) );
		return true;
	}

	/**
	 * Add job to queue.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int    $term_id Term ID.
	 * @return void
	 * @since 1.0.0
	 */
	public function add( string $taxonomy, int $term_id ): void {
		if ( ! isset( self::$jobs[ $taxonomy ] ) ) {
			self::$jobs[ $taxonomy ] = array();
		}

		if ( ! in_array( $term_id, self::$jobs[ $taxonomy ], true ) ) {
			self::$jobs[ $taxonomy ][] = $term_id;
		}
	}

	/**
	 * Delete job from queue.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int    $term_id Term ID.
	 * @return void
	 * @since 1.0.0
	 */
	public function delete( string $taxonomy, int $term_id ) {
		if ( ! isset( self::$jobs[ $taxonomy ] ) ) {
			return;
		}

		$key = array_search( $term_id, self::$jobs[ $taxonomy ], true );
		if ( false !== $key ) {
			unset( self::$jobs[ $taxonomy ][ $key ] );
		}
	}

	public function get_jobs(): ?array {
		return self::$jobs;
	}

	/**
	 * Save jobs queue.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function save() {
		update_option( Options::KEY_JOBS, self::$jobs );
	}
}
