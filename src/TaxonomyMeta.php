<?php
/**
 * Get extended taxonomy meta. If not found, add to job queue.
 *
 * @package     DRPPSM\TaxonomyMeta
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 *
 * - Adds Job to queue if meta not found.
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\SingletonTrait;
use WP_Exception;

defined( 'ABSPATH' ) || exit;

/**
 * Get extended taxonomy meta. If not found, add to job queue.
 *
 * @package     DRPPSM\TaxonomyMeta
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 *
 * - Adds Job to queue if meta not found.
 */
class TaxonomyMeta implements Executable, Registrable {

	use SingletonTrait;

	/**
	 * Jobs queue.
	 *
	 * @var null|array
	 * @since 1.0.0
	 */
	private static null|array $jobs = null;

	/**
	 * Meta data.
	 *
	 * @var null|array
	 * @since 1.0.0
	 */
	private static null|array $meta = null;

	/**
	 * TaxonomyMeta constructor.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		if ( ! isset( self::$jobs ) ) {
			self::$jobs = array();
			$jobs       = get_option( Options::KEY_JOBS );

			if ( is_array( $jobs ) ) {
				self::$jobs = $jobs;
			}
		}

		if ( ! isset( self::$meta ) ) {
			self::$meta = array();
			$meta       = get_option( Options::KEY_TAX_META );

			if ( is_array( $meta ) ) {
				self::$meta = $meta;
			}
		}
	}

	/**
	 * Execute the hooks.
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
	 * Register the hooks.
	 *
	 * @return bool|null
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		if ( has_action( 'shutdown', array( $this, 'shutdown' ) ) ) {
			return true;
		}

		add_action( 'shutdown', array( $this, 'shutdown' ) );

		$taxonomies = array_values( DRPPSM_TAX_MAP );
		foreach ( $taxonomies as $taxonomy ) {
			$action = "get_{$taxonomy}_meta_extd";
			add_action( $action, array( $this, 'get_taxonomy_meta_extd' ), 10, 2 );
		}
		return true;
	}

	/**
	 * Get taxonomy extended meta. If not found, add to job queue.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int    $term_id Term id.
	 * @return void
	 * @since 1.0.0
	 */
	public function get_taxonomy_meta_extd( string $taxonomy, int $term_id ): ?array {

		$key  = self::get_data_key( $taxonomy );
		$meta = get_term_meta( $term_id, $key, true );
		if ( ! isset( $meta ) || ! $meta ) {
			$this->add_job( $taxonomy, $term_id );
			return null;
		}

		return $meta;
	}


	/**
	 * Get meta key for extended taxonomy meta.
	 *
	 * @param string $taxonomy
	 * @return string
	 */
	public static function get_data_key( string $taxonomy ): string {
		return "{$taxonomy}_info";
	}

	public static function get_runner_key( string $taxonomy ): string {
		return "{$taxonomy}_runner";
	}

	/**
	 * Shutdown and write jobs & meta once.
	 *
	 * @return bool True on success otherwise false.
	 * @since 1.0.0
	 */
	public function shutdown(): bool {

		// Prevent recursion.
		if ( did_action( 'drppsm_job_runner' ) ) {
			Logger::debug( 'SHUTDOWN RECURSION PREVENTED' );
			return false;
		}

		return update_option( Options::KEY_JOBS, self::$jobs );
	}

	/**
	 * Add job to queue.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int    $term_id Term id.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_job( string $taxonomy, int $term_id ): void {

		// Prevent recursion.
		if ( did_action( 'drppsm_job_runner' ) ) {
			Logger::debug( 'POSSIBLE RECURSION PREVENTED' );
			return;
		}

		Logger::debug(
			array(
				'MESSAGE'  => 'ADDING JOB',
				'TAXONOMY' => $taxonomy,
				'TERM_ID'  => $term_id,
			)
		);

		if ( ! isset( self::$jobs[ $taxonomy ] ) ) {
			self::$jobs[ $taxonomy ] = array();
		}

		if ( ! in_array( $term_id, self::$jobs[ $taxonomy ], true ) ) {
			self::$jobs[ $taxonomy ][] = $term_id;
		}

		Logger::debug( array( 'JOBS' => self::$jobs ) );
	}
}
