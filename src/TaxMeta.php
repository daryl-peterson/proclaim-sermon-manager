<?php
/**
 * Get extended taxonomy meta. If not found, add to job queue.
 *
 * @package     DRPPSM\TaxMeta
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
use WP_Term;

defined( 'ABSPATH' ) || exit;

/**
 * Get extended taxonomy meta. If not found, add to job queue.
 *
 * @package     DRPPSM\TaxMeta
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 *
 * - Adds Job to queue if meta not found.
 */
class TaxMeta implements Executable, Registrable {


	/**
	 * SchedulerJobs instance.
	 *
	 * @var SchedulerJobs
	 * @since 1.0.0
	 */
	private static SchedulerJobs $jobs;

	/**
	 * TaxonomyMeta constructor.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		self::$jobs = SchedulerJobs::get_instance();
	}

	/**
	 * Execute the hooks.
	 *
	 * @return self
	 * @since 1.0.0
	 */
	public static function exec(): self {
		$obj = new self();
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

		if ( has_action( 'get_drppsm_series_meta_extd', array( $this, 'get_taxonomy_meta' ) ) ) {
			return null;
		}

		$taxonomies = array_values( DRPPSM_TAX_MAP );
		foreach ( $taxonomies as $taxonomy ) {
			add_filter( "get_{$taxonomy}_meta_extd", array( $this, 'get_taxonomy_meta' ), 10, 2 );
			add_action( "check_{$taxonomy}_meta_extd", array( $this, 'check_taxonomy_meta' ), 10, 2 );
			add_action( "created_{$taxonomy}", array( $this, 'created_taxonomy' ), 10, 4 );
			add_action( "edited_{$taxonomy}", array( $this, 'edited_taxonomy' ), 10, 4 );
			add_action( "delete_{$taxonomy}", array( $this, 'delete_taxonomy' ), 10, 4 );
		}
		return true;
	}

	/**
	 * Check if taxonomy meta exists. If not, add to job queue.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int    $term_id Term id.
	 * @since 1.0.0
	 */
	public function check_taxonomy_meta( string $taxonomy, int $term_id ): void {
		$key = self::get_data_key( $taxonomy );
		$has = metadata_exists( 'term', $term_id, $key );
		if ( ! $has ) {
			self::$jobs->add( $taxonomy, $term_id );
		}
	}

	/**
	 * Get taxonomy extended meta. If not found, add to job queue.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int    $term_id Term id.
	 * @return null|TaxInfo
	 * @since 1.0.0
	 */
	public function get_taxonomy_meta( string $taxonomy, int $term_id ): ?TaxInfo {

		$key  = self::get_data_key( $taxonomy );
		$meta = get_term_meta( $term_id, $key, true );

		Logger::debug(
			array(
				'TAXONOMY' => $taxonomy,
				'TERM_ID'  => $term_id,
				'META'     => $meta,
			)
		);
		if ( ! isset( $meta ) || ! $meta ) {
			self::$jobs->add( $taxonomy, $term_id );
			return null;
		}
		return $meta;
	}

	/**
	 * Add taxonomy to job queue.
	 *
	 * @param int   $term_id
	 * @param int   $tt_id
	 * @param array $args
	 * @since 1.0.0
	 */
	public function created_taxonomy(
		int $term_id,
		int $tt_id,
		array $args
	) {
		global $taxnow;

		$taxonomy = $taxnow;

		// If taxonomy is not set, get it from term_id.
		if ( ! isset( $taxonomy ) || empty( $taxonomy ) ) {
			$taxonomy = get_term_by( 'term_id', $term_id );
		}

		// If taxonomy is still not set, return.
		if ( ! isset( $taxonomy ) || empty( $taxonomy ) ) {
			return;
		}

		self::$jobs->add( $taxonomy, $term_id );
	}

	/**
	 * Add taxonomy to job queue.
	 *
	 * @param int   $term_id
	 * @param int   $tt_id
	 * @param array $args
	 * @since 1.0.0
	 */
	public function edited_taxonomy(
		int $term_id,
		int $tt_id,
		array $args
	) {
		global $taxnow;

		$taxonomy = $taxnow;

		// If taxonomy is not set, get it from term_id.
		if ( ! isset( $taxonomy ) || empty( $taxonomy ) ) {
			$taxonomy = get_term_by( 'term_id', $term_id );
		}

		// If taxonomy is still not set, return.
		if ( ! isset( $taxonomy ) || empty( $taxonomy ) ) {
			return;
		}

		self::$jobs->add( $taxonomy, $term_id );
	}

	public function delete_taxonomy(
		int $term_id,
		int $tax_id,
		WP_Term $deleted_term,
		array $bject_ids
	) {
		Logger::debug(
			array(
				'TERM_ID' => $term_id,
				'TAX_ID'  => $tax_id,
				'TERM'    => $deleted_term,
				'OBJECTS' => $bject_ids,
			)
		);
	}

	/**
	 * Get meta key for extended taxonomy meta.
	 *
	 * @param string $taxonomy
	 * @return string
	 * @since 1.0.0
	 */
	public static function get_data_key( string $taxonomy ): string {
		return "{$taxonomy}_info";
	}

	public static function get_runner_key( string $taxonomy ): string {
		return "{$taxonomy}_runner";
	}

	/**
	 * Update term meta.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int    $term_id Term ID.
	 * @return bool
	 * @since 1.0.0
	 */
	public static function update_term_meta( string $taxonomy, int $term_id ): ?TaxInfo {
		$obj = new TaxInfo( $taxonomy, absint( $term_id ) );

		if ( isset( $obj->ready ) && true === $obj->ready ) {
			$key = self::get_data_key( $taxonomy );
			update_term_meta( $term_id, $key, $obj );
			self::$jobs->delete( $taxonomy, $term_id );
			return $obj;

		}
		return null;
	}
}
