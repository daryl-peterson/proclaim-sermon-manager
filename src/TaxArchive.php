<?php
/**
 * Taxonomy Archive Class
 *
 * @package     DRPPSM\TaxArchive
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use stdClass;
use WP_Post;
use WP_Term;

defined( 'ABSPATH' ) || exit;

/**
 * Taxonomy Archive Class
 *
 * @package     DRPPSM\TaxArchive
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxArchive {

	/**
	 * Taxonomy name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_name;

	/**
	 * Used in paginated queries, per_page
	 *
	 * @var int
	 * @since 1.0.0
	 */
	private int $per_page;

	/**
	 * Order.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $order;

	/**
	 * Order by.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $orderby;

	/**
	 * Used in paginated queries, per_page
	 *
	 * @var int
	 * @since 1.0.0
	 */
	private int $number;

	/**
	 * Query offset.
	 *
	 * @var int
	 * @since 1.0.0
	 */
	private int $offset;

	/**
	 * Pagination arguments.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private null|array $paginate;

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
	 * Template data.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private null|array $data;

	/**
	 * Query arguments.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $args;


	/**
	 * Initialize object.
	 *
	 * @param string $tax_name Taxonomy name.
	 * @param string $term_name Term name.
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct( string $tax_name, string $slug ) {
		$this->tax_name = $tax_name;
		$this->slug     = $slug;
		$this->term     = null;
		$this->data     = null;

		$term = get_term_by( 'slug', $this->slug, $this->tax_name );
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
		$this->data = $this->get_post_data();
		$this->render();
	}

	private function test() {

		$terms       = get_terms( DRPPSM_TAX_SERIES );
		$posts_array = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => DRPPSM_PT_SERMON,
				'tax_query'      => array(
					array(
						'taxonomy' => DRPPSM_TAX_SERIES,
						'field'    => 'slug',
						'terms'    => wp_list_pluck( $terms, 'slug' ),
					),
				),
			)
		);

		/*
		//fetch all reviews which have no assigned term in 'review-product'
		$taxonomy  = 'review-product';
		$post_type = 'reviews';
		$args = [
			'post_type' => $post_type,
			'tax_query' => [
				[
					'taxonomy' => $taxonomy,
					'terms'    => get_terms( $taxonomy, [ 'fields' => 'ids'  ] ),
					'operator' => 'NOT IN'
				]
			]
		];

		$query = new \WP_Query( $args );


		*/

		Logger::debug( $posts_array );
	}

	/**
	 * Render template.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function render() {
		$output = '';
		if ( isset( $this->data ) && is_array( $this->data ) && count( $this->data ) > 0 ) {

			ob_start();
			get_partial(
				Templates::TAX_ARCHIVE,
				array(
					'list' => $this->data,
					'term' => $this->term,

				)
			);
			get_partial( Templates::Pagination, $this->paginate );

			$output .= ob_get_clean();
		} else {
			ob_start();
			get_partial( 'no-posts' );
			$output .= ob_get_clean();
		}
		echo $output;
	}

	/**
	 * Get post data.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	private function get_post_data(): mixed {

		$args = $this->args;
		if ( isset( $args['meta_query'] ) ) {
			unset( $args['meta_query'] );
		}

		$page      = get_page_number();
		$trans_key = "{$this->tax_name}_{$this->term->term_id }_{$page}";
		$trans     = Transient::get( $trans_key );

		if ( $trans ) {
			Logger::debug( 'Using transient' );
			return $trans;
		}

		// Set arguments from pagination.
		$this->args['number'] = $this->number;
		$this->args['offset'] = $this->offset;

		$post_data = get_posts( $this->args );
		$data      = array();

		/**
		 * @var WP_Post $post_item
		 */
		foreach ( $post_data as $post_item ) {
			$post_item              = $this->get_sermon_meta( $post_item );
			$post_item              = $this->get_sermon_terms( $post_item );
			$data[ $post_item->ID ] = $post_item;
		}
		Transient::set( $trans_key, $data, Transient::TAX_ARCHIVE_TTL );

		return $data;
	}

	/**
	 * Set sermon meta.
	 *
	 * @param WP_Post $post_item
	 * @return WP_Post
	 * @since 1.0.0
	 */
	private function get_sermon_meta( WP_Post $post_item ): WP_Post {
		$meta = SermonMeta::get_meta( $post_item->ID );

		$fmt = Settings::get( Settings::DATE_FORMAT );

		$post_item->meta = new stdClass();

		// Set meta object properties.
		foreach ( $meta as $meta_key => $meta_value ) {
			$post_item->meta->{$meta_key} = $meta_value;
		}

		// Format date.
		if ( isset( $post_item->meta->date ) && ! empty( $post_item->meta->date ) ) {
			$post_item->meta->date = date_i18n( $fmt, $post_item->meta->date );
		}
		return $post_item;
	}

	/**
	 * Get sermon terms.
	 *
	 * @param WP_Post $post_item
	 * @return WP_Post
	 * @since 1.0.0
	 */
	private function get_sermon_terms( WP_Post $post_item ): WP_Post {

		foreach ( DRPPSM_TAX_MAP as $tax_name ) {
			$terms = get_the_terms( $post_item, $tax_name );
			if ( is_wp_error( $terms ) || ! is_array( $terms ) || 0 === count( $terms ) ) {
				$post_item->{$tax_name} = null;
				continue;
			}
			$term                   = array_shift( $terms );
			$post_item->{$tax_name} = $term;
		}

		return $post_item;
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
					'taxonomy' => $this->tax_name,
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

	/**
	 * Set pagination data.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function set_pagination() {
		global $post;

		$this->paginate = null;

		$term_count = $this->get_post_count();
		if ( ! $term_count ) {
			return;
		}

		$tpp           = $this->per_page;
		$max_num_pages = ceil( $term_count / $tpp );
		$paged         = get_page_number();

		// Calculate term offset
		$offset = ( ( $paged - 1 ) * $tpp );

		// We can now get our terms and paginate it
		$this->number = $tpp;
		$this->offset = $offset;

		$this->paginate = array(

			'current' => $paged,
			'total'   => $max_num_pages,
			'post_id' => $post->ID,
		);
	}

	/**
	 * Get post count.
	 *
	 * @return int Post count.
	 * @since 1.0.0
	 */
	private function get_post_count(): int {
		$args = $this->args;

		$args['fields']         = 'ids';
		$args['posts_per_page'] = -1;

		$unset = array(
			'meta_query',
			'orderby',
			'order',
		);
		foreach ( $unset as $key ) {
			if ( key_exists( $key, $args ) ) {
				unset( $args[ $key ] );
			}
		}
		$posts  = get_posts( $args );
		$result = count( $posts );

		return $result;
	}
}
