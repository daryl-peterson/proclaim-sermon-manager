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

use DRPPSM\Constants\Meta;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use stdClass;
use WP_Post;
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
			if ( ! in_array( $key, $suffix ) ) {
				unset( $meta[ $key ] );
				continue;
			}
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


	public static function get_runner_key( string $taxonomy ): string {
		return "{$taxonomy}_runner";
	}

	/**
	 * Set term meta.
	 *
	 * @param int   $term_id Term ID.
	 * @param array $args Arguments.
	 * @since 1.0.0
	 */
	private function set_term_meta( int $term_id, array $args ): void {
		if ( ! isset( $args['taxonomy'] ) ) {
			return;
		}
		$tax = $args['taxonomy'];
		if ( ! isset( $args[ $tax . '_image_id' ] ) ) {
			delete_term_meta( $term_id, $tax . '_image' );
			delete_term_meta( $term_id, $tax . '_image_id' );
			delete_term_meta( $term_id, $tax . '_date' );
			Logger::debug( 'Deleted meta' );
		} else {
			$image_id = get_term_meta( $term_id, $tax . '_image_id', true );
			Logger::debug(
				array(
					'TERM ID'  => $term_id,
					'IMAGE ID' => $image_id,
				)
			);
			if ( ! $image_id && empty( $image_id ) ) {
				Logger::debug( 'DONT SET DATE META' );
				delete_term_meta( $term_id, $tax . '_date' );
				return;
			}

			$this->set_date_meta( $tax, $term_id, $tax . '_date' );
		}
	}

	/**
	 * Set date meta.
	 *
	 * @param string $tax_name Taxonomy name.
	 * @param int    $term_id Term ID.
	 * @param string $key_name Meta key name.
	 * @param bool   $recent Flag to get oldest or newest.
	 * @since 1.0.0
	 */
	private function set_date_meta( string $tax_name, int $term_id, string $key_name, bool $recent = true ): void {

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
				'meta_key'     => Meta::DATE,
				'meta_value'   => time(),
				'meta_compare' => '<=',
			),

		);
		$post_list = get_posts( $args );

		if ( is_wp_error( $post_list ) || ! is_array( $post_list ) || ! count( $post_list ) > 0 ) {
			return;
		}

		$post_item = array_shift( $post_list );
		if ( ! $post_item ) {
			return;
		}

		$meta = get_post_meta( $post_item->ID, Meta::DATE, true );

		if ( ! isset( $meta ) || empty( $meta ) ) {
			return;
		}
		Logger::debug(
			array(
				'TERM ID'  => $term_id,
				'KEY NAME' => $key_name,
				'META'     => $meta,
			)
		);

		update_term_meta( $term_id, $key_name, $meta );
	}

	/**
	 * After post edit update term meta.
	 *
	 * @param string $tax_name Taxonomy name.
	 * @param int    $term_id Term ID.
	 * @since 1.0.0
	 */
	public function post_edit( int $post_id, WP_Post $post_item ) {
		$taxonomies = array_values( DRPPSM_TAX_MAP );
		$data       = array();
		foreach ( $taxonomies as $tax_name ) {
			$term_item = get_the_terms( $post_id, $tax_name );
			if ( is_wp_error( $term_item ) || ! is_array( $term_item ) || 0 === count( $term_item ) ) {
				continue;
			}
			$term_item = array_shift( $term_item );

			$this->set_date_meta( $tax_name, $term_item->term_id, $tax_name . '_date' );
		}
	}
}
