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

use CPM\Sermon;
use DateTimeZone;
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
	 * @param string $tax_name
	 * @param string $term_name
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct( string $tax_name, string $slug ) {
		$this->tax_name = $tax_name;
		$this->slug     = $slug;
		$this->term     = null;

		$term = get_term_by( 'slug', $this->slug, $this->tax_name );
		if ( is_wp_error( $term ) || ! $term ) {
			return;
		}

		if ( is_array( $term ) && 0 !== count( $term ) ) {
			$this->term = array_shift( $term );
		} else {
			$this->term = $term;
		}
		$data = $this->get_post_data();
		Logger::debug( $data );
	}

	/**
	 * Get post data.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	private function get_post_data(): mixed {
		$per_page = Settings::get( Settings::SERMON_COUNT );
		$order    = Settings::get( Settings::ARCHIVE_ORDER );
		$orderby  = Settings::get( Settings::ARCHIVE_ORDER_BY );

		$args = array(
			'post_type'      => DRPPSM_PT_SERMON,
			'orderby'        => 'meta_value_num',
			'order'          => $order,
			'posts_per_page' => $per_page,
			'tax_query'      => array(
				array(
					'taxonomy' => $this->tax_name,
					'field'    => 'term_id',
					'terms'    => $this->term->term_id,
				),
			),
		);

		if ( $orderby === 'date_preached' ) {
			$args['meta_query'] = array(
				'orderby'      => 'meta_value_num',
				'meta_key'     => SermonMeta::DATE,
				'meta_value'   => time(),
				'meta_compare' => '<=',
			);
			$args['orderby']    = 'meta_value_num';
			$args['order']      = $order;
		}

		$data = get_posts( $args );
		foreach ( $data as $key => $post_item ) {
			$data[ $key ] = $this->set_sermon_meta( $post_item );
		}
		return $data;
	}

	/**
	 * Set sermon meta.
	 *
	 * @param WP_Post $post_item
	 * @return WP_Post
	 * @since 1.0.0
	 */
	private function set_sermon_meta( WP_Post $post_item ): WP_Post {
		$meta = SermonMeta::get_meta( $post_item->ID );

		$fmt_date = get_option( 'date_format' );
		$fmt_time = get_option( 'time_format' );
		$fmt      = $fmt_date . ' ' . $fmt_time;

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
}
