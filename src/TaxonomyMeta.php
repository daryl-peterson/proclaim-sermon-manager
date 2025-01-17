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

	/**
	 * TaxonomyMeta constructor.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
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

		if ( has_action( 'get_drppsm_series_meta_extd', array( $this, 'get_taxonomy_meta_extd' ) ) ) {
			return false;
		}

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

		$options = get_option( Options::KEY_TAX_META );

		if ( ! is_array( $options ) ) {
			$this->add_job( $taxonomy, $term_id );
			return null;
		}
		if ( ! isset( $options[ $taxonomy ][ $term_id ] ) ) {
			$this->add_job( $taxonomy, $term_id );
			return null;
		}

		return $options[ $taxonomy ][ $term_id ];
	}

	/**
	 * Set taxonomy extended meta.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int    $term_id Term id.
	 * @param array  $meta Meta data.
	 * @return bool
	 */
	public function set_taxonomy_meta_extd( string $taxonomy, int $term_id, array $meta ): bool {
		$options = get_option( Options::KEY_TAX_META );

		if ( ! is_array( $options ) ) {
			$options = array();
		}

		if ( ! isset( $options[ $taxonomy ] ) ) {
			$options[ $taxonomy ] = array();
		}

		$options[ $taxonomy ][ $term_id ] = $meta;
		return update_option( Options::KEY_TAX_META, $options );
	}

	/**
	 * Add job to queue.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int    $term_id Term id.
	 * @return void
	 */
	private function add_job( string $taxonomy, int $term_id ): void {

		Logger::debug(
			array(
				'MESSAGE'  => 'ADDING JOB',
				'TAXONOMY' => $taxonomy,
				'TERM_ID'  => $term_id,
			)
		);
		$options = get_option( Options::KEY_JOBS );
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$options[ $taxonomy ][ $term_id ] = true;
		update_option( Options::KEY_JOBS, $options );
	}
}
