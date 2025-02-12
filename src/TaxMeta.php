<?php
/**
 * Get / Set taxonomy meta.
 *
 * @package     DRPPSM\TaxMeta
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;
use stdClass;
use WP_Post;
use WP_Term;

/**
 * Get / Set taxonomy meta.
 *
 * - Deletes transients when a taxonomy is created, edited, or deleted.
 *
 * @package     DRPPSM\TaxMeta
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxMeta implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Series attachment post id.
	 *
	 * @since 1.0.0
	 */
	public const SERIES_IMAGE_ID = 'drppsm_series_image_id';

	/**
	 * Series image file and path.
	 *
	 * @since 1.0.0
	 */
	public const SERIES_IMAGE = 'drppsm_series_image';

	/**
	 * Preacher attachment post id.
	 *
	 * @since 1.0.0
	 */
	public const PREACHER_IMAGE_ID = 'drppsm_preacher_image_id';

	/**
	 * Preacher image file and path.
	 *
	 * @since 1.0.0
	 */
	public const PREACHER_IMAGE = 'drppsm_preacher';

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
	 * Register the hooks.
	 *
	 * @return bool|null
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		if ( has_action( 'get_drppsm_series_meta_extd', array( $this, 'get_taxonomy_meta' ) ) ) {
			return false;
		}

		$taxonomies = array_values( DRPPSM_TAX_MAP );
		foreach ( $taxonomies as $taxonomy ) {
			add_filter( "get_{$taxonomy}_meta_extd", array( $this, 'get_taxonomy_meta' ), 10, 2 );
			add_action( "created_{$taxonomy}", array( $this, 'created_taxonomy' ), 10, 3 );
			add_action( "edited_{$taxonomy}", array( $this, 'edited_taxonomy' ), 10, 3 );
			add_action( "delete_{$taxonomy}", array( $this, 'delete_taxonomy' ), 10, 4 );
		}
		$pt = DRPPSM_PT_SERMON;
		add_action( "edit_post_{$pt}", array( $this, 'post_edit' ), 10, 2 );
		return true;
	}

	/**
	 * Get taxonomy extended meta. If not found, add to job queue.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int    $term_id Term id.
	 * @return null|stdClass
	 * @since 1.0.0
	 */
	public function get_taxonomy_meta( string $taxonomy, int $term_id ): ?stdClass {
		$suffix = array(
			"{$taxonomy}_date",
			"{$taxonomy}_image_id",
			"{$taxonomy}_image",
		);

		$suffix_map = array(
			"{$taxonomy}_date"     => 'date',
			"{$taxonomy}_image_id" => 'image_id',
			"{$taxonomy}_image"    => 'image',
		);

		$meta = get_term_meta( $term_id );

		if ( ! isset( $meta ) || ! is_array( $meta ) || 0 === count( $meta ) ) {
			self::$jobs->add( $taxonomy, $term_id );
			return null;
		}

		$obj = new stdClass();
		foreach ( $meta as $key => $value ) {

			// @codeCoverageIgnoreStart
			if ( ! in_array( $key, $suffix ) ) {
				unset( $meta[ $key ] );
				continue;
			}
			// @codeCoverageIgnoreEnd

			$obj->{$suffix_map[ $key ]} = maybe_unserialize( $value[0] );

		}
		$term_obj = get_term_by( 'term_id', $term_id, $taxonomy );
		if ( $term_obj ) {
			$obj->object = $term_obj;
		}

		return $obj;
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
		$this->set_term_meta( $term_id, $args );
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
		$this->set_term_meta( $term_id, $args );
		Transient::delete_all();
	}

	/**
	 * Taxonomy has been deleted.
	 *
	 * @param int     $term_id Term ID.
	 * @param int     $tax_id Taxonomy ID.
	 * @param WP_Term $deleted_term Term object.
	 * @param array   $bject_ids
	 * @return void
	 * @since 1.0.0
	 */
	public function delete_taxonomy(
		int $term_id,
		int $tax_id,
		WP_Term $deleted_term,
		array $bject_ids
	) {

		Transient::delete_all();
	}

	/**
	 * Set term meta.
	 *
	 * @param int   $term_id Term ID.
	 * @param array $args Arguments.
	 * @return bool
	 * @since 1.0.0
	 *
	 * @todo Verify this is correct.
	 */
	private function set_term_meta( int $term_id, array $args ): bool {
		if ( ! isset( $args['taxonomy'] ) ) {
			return false;
		}
		$tax = $args['taxonomy'];

		if ( ! isset( $args[ $tax . '_image_id' ] ) ) {
			return false;
		}

		$image_id = get_term_meta( $term_id, $tax . '_image_id', true );

		if ( ! $image_id && empty( $image_id ) ) {
			Logger::debug( 'DONT SET DATE META' );
			delete_term_meta( $term_id, $tax . '_date' );
			return false;
		}

		return $this->set_date_meta( $tax, $term_id, $tax . '_date' );
	}

	/**
	 * Set date meta.
	 *
	 * @param string $tax_name Taxonomy name.
	 * @param int    $term_id Term ID.
	 * @param string $key_name Meta key name.
	 * @param bool   $recent Flag to get oldest or newest.
	 * @return bool
	 * @since 1.0.0
	 */
	private function set_date_meta( string $tax_name, int $term_id, string $key_name, bool $recent = true ): bool {

		$order = $recent ? 'ASC' : 'DESC';
		$args  = array(
			'post_type'   => DRPPSM_PT_SERMON,
			'numberposts' => 1,
			'order'       => $order,
			'orderby'     => 'meta_value_num',
			'tax_query'   => array(
				array(
					'taxonomy'         => $tax_name,
					'field'            => 'term_id',
					'terms'            => $term_id,
					'include_children' => false,
				),

			),
			'meta_query'  => array(
				'orderby'      => 'meta_value_num',
				'meta_key'     => SermonMeta::DATE,
				'meta_value'   => time(),
				'meta_compare' => '<=',
			),

		);
		$post_list = get_posts( $args );

		if ( is_wp_error( $post_list ) || ! is_array( $post_list ) || ! count( $post_list ) > 0 ) {
			return false;
		}

		$post_item = array_shift( $post_list );
		// @codeCoverageIgnoreStart
		if ( ! $post_item ) {
			return false;
		}
		// @codeCoverageIgnoreEnd

		$meta = get_post_meta( $post_item->ID, SermonMeta::DATE, true );

		// @codeCoverageIgnoreStart
		if ( ! isset( $meta ) || empty( $meta ) ) {
			return false;
		}
		// @codeCoverageIgnoreEnd

		$result = update_term_meta( $term_id, $key_name, $meta );

		// @codeCoverageIgnoreStart
		if ( is_wp_error( $result ) ) {
			return false;
		}
		// @codeCoverageIgnoreEnd

		return true;
	}

	/**
	 * After post edit update term meta.
	 *
	 * @param string $tax_name Taxonomy name.
	 * @param int    $term_id Term ID.
	 * @return bool True if successful.
	 * @since 1.0.0
	 */
	public function post_edit( int $post_id, WP_Post $post_item ): bool {
		$success    = false;
		$taxonomies = array_values( DRPPSM_TAX_MAP );
		$data       = array();
		foreach ( $taxonomies as $tax_name ) {
			$term_item = get_the_terms( $post_id, $tax_name );
			if ( is_wp_error( $term_item ) || ! is_array( $term_item ) || 0 === count( $term_item ) ) {
				continue;
			}
			$term_item = array_shift( $term_item );

			$this->set_date_meta( $tax_name, $term_item->term_id, $tax_name . '_date' );
			$success = true;
		}
		Transient::delete_all();
		return $success;
	}
}
