<?php
/**
 * Display taxonomy archive.
 *
 * - Used for viewing sermons by taxonomy.
 *
 * @package     DRPPSM\TaxDisplayArchive
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Data\Sermon;
use WP_Post;
use WP_Term;

/**
 * Display taxonomy archive.
 *
 * - Used for viewing sermons by taxonomy.
 *
 * @package     DRPPSM\TaxDisplayArchive
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxDisplayArchive extends TaxDisplay {

	/**
	 * Term slug.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $slug;

	/**
	 * Term object.
	 *
	 * @var WP_Term
	 * @since 1.0.0
	 */
	private ?WP_Term $term;

	/**
	 * Initialize object.
	 *
	 * @param array $args Shortcode arguments.
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct( array $args = array() ) {

		if ( ! $this->is_args_valid( $args ) ) {
			return;
		}

		$term = get_term_by( 'slug', $this->slug, $this->taxonomy );
		if ( is_wp_error( $term ) || ! $term ) {
			return;
		}

		if ( is_array( $term ) && 0 !== count( $term ) ) {
			$this->term = array_shift( $term );
		} else {
			$this->term = $term;
		}
		$this->set_params();
		$this->set_pagination();
		$this->set_data();

		$params = array(
			'list' => $this->data,
			'term' => $this->term,

		);
		$this->show_template( Template::TAX_ARCHIVE, $params );
	}

	/**
	 * Get record count
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public function get_count(): int {
		if ( ! $this->term ) {
			return 0;
		}

		return $this->term->count;
	}

	/**
	 * Validate arguments.
	 *
	 * @param array $args Shortcode arguments.
	 * @return bool
	 * @since 1.0.0
	 */
	protected function is_args_valid( array $args ): bool {
		$this->taxonomy = null;
		$this->term     = null;
		$this->data     = null;

		if ( ! isset( $args['display'] ) || ! isset( $args['term'] ) ) {
			return false;
		}

		$this->taxonomy = TaxUtils::get_taxonomy_name( $args['display'] );
		if ( ! $this->taxonomy ) {
			return false;
		}

		$this->slug = $args['term'];
		return true;
	}

	/**
	 * Get post data.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	protected function set_data(): void {

		$args = $this->args;
		if ( isset( $args['meta_query'] ) ) {
			unset( $args['meta_query'] );
		}

		$page      = get_page_number();
		$trans_key = "{$this->taxonomy}_{$this->term->term_id }_{$page}";
		$trans     = Transient::get( $trans_key );

		if ( $trans ) {
			Logger::debug( "Using transient : $trans_key" );
			$this->data = $trans;
			return;
		}

		// Set arguments from pagination.
		$this->args['number'] = $this->per_page;
		$this->args['offset'] = $this->offset;

		$post_data = get_posts( $this->args );
		$data      = array();

		/**
		 * @var WP_Post $post_item
		 */
		foreach ( $post_data as $post_item ) {
			// $post_item              = $this->get_sermon_meta( $post_item );
			// $post_item              = $this->get_sermon_terms( $post_item );
			$data[ $post_item->ID ] = new Sermon( $post_item );
		}
		Transient::set( $trans_key, $data, Transient::TTL_12_HOURS );
		$this->data = $data;
	}

	/**
	 * Set query params.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function set_params() {
		$this->per_page = Settings::get( Settings::SERMON_COUNT );
		$this->order    = Settings::get( Settings::ARCHIVE_ORDER );
		$this->orderby  = Settings::get( Settings::ARCHIVE_ORDER_BY );

		$args = array(
			'post_type'      => DRPPSM_PT_SERMON,
			'orderby'        => 'meta_value_num',
			'order'          => $this->order,
			'posts_per_page' => $this->per_page,
			'tax_query'      => array(
				array(
					'taxonomy' => $this->taxonomy,
					'field'    => 'term_id',
					'terms'    => $this->term->term_id,
				),
			),
		);

		if ( $this->orderby === 'date_preached' ) {
			$args['meta_query'] = array(
				'orderby'      => 'meta_value_num',
				'meta_key'     => SermonMeta::DATE,
				'meta_value'   => time(),
				'meta_compare' => '<=',
			);
			$args['orderby']    = 'meta_value_num';
			$args['order']      = $this->order;
			$args['meta_key']   = SermonMeta::DATE;
		}
		$this->args = $args;
	}
}
