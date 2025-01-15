<?php
/**
 * Reusable tax queries.
 *
 * @package     DRPPSM\TaxQueries
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Reusable tax queries.
 *
 * @package     DRPPSM\TaxQueries
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxQueries {

	/**
	 * Get terms with images and sermons.
	 *
	 * @param string      $taxonomy Taxonomy name.
	 * @param null|string $order Order. (ASC|DESC)
	 * @param null|string $orderby Order by. See below.
	 * @param null|int    $number Number of terms to return.
	 * @param null|int    $offset Offset.
	 * @param null|int    $add_meta Add additional meta query.
	 * @return null|array
	 *
	 * #### Orderby
	 * - name
	 * - slug
	 * - term_group
	 * - term_id
	 * - id
	 * - description
	 * - parent
	 * - count
	 * - term_taxonomy_id
	 */
	public static function get_terms_with_images(
		string $taxonomy,
		null|string $order,
		null|string $orderby,
		null|int $number = null,
		null|int $offset = null,
		null|array $add_meta = null
	): ?array {
		$query_args = self::get_query_args( $taxonomy, $order, $orderby, $number, $offset );

		if ( is_array( $add_meta ) ) {
			$query_args['meta_query'][] = $add_meta;
		}

		$list = get_terms( $query_args );
		if ( $list instanceof WP_Error ) {
			return null;
		}
		if ( is_string( $list ) ) {
			$list = array( $list );
		}
		return $list;
	}

	/**
	 * Get term count.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @return int
	 * @since 1.0.0
	 */
	public static function get_term_count( string $taxonomy ): int {
		$query_args = array(
			'taxonomy' => $taxonomy,
			'fields'   => 'count',
		);

		// @codingStandardsIgnoreStart
		$query_args['meta_query'][] = self::get_meta_query($taxonomy);

		$term_count = get_terms( $query_args );
		if ($term_count instanceof WP_Error){
			return 0;
		}
		return absint($term_count);

	}

	/**
	 * Get term posts.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int    $term_id Term ID.
	 * @return array
	 * @since 1.0.0
	 */
	public static function get_term_posts( string $taxonomy, int $term_id ): array {
		$query_args = array(
			'post_type' => DRPPSM_PT_SERMON,
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $term_id,
				),
			),
		);

		$posts = get_posts( $query_args );
		if ( $posts instanceof WP_Error ) {
			return array();
		}
		return $posts;
	}

	/**
	 * Get query arguments.
	 *
	 * @param string      $taxonomy Taxonomy name.
	 * @param null|string $order Order. (ASC|DESC)
	 * @param null|string $orderby Order by.
	 * @param null|int    $number Number of terms to return.
	 * @param null|int    $offset Offset.
	 * @return array
	 * @since 1.0.0
	 *
	 * #### Orderby
	 * - name
	 * - slug
	 * - term_group
	 * - term_id
	 * - id
	 * - description
	 * - parent
	 * - count
	 * - term_taxonomy_id
	 */
	private static function get_query_args(
		string $taxonomy,
		null|string $order,
		null|string $orderby,
		null|int $number,
		null|int $offset
	): array {

		$query_args = array(
			'taxonomy' => $taxonomy,
		);

		if ( is_string( $order) && is_string( $orderby ) ) {
			$query_args['order']   = $order;
			$query_args['orderby'] = $orderby;
		}

		if ( is_int( $number ) ) {
			$query_args['number'] = $number;
		}

		if ( is_int( $offset ) ) {
			$query_args['offset'] = $offset;
		}

		// @codingStandardsIgnoreStart
		$query_args['meta_query'][] = self::get_meta_query($taxonomy);
		// @codingStandardsIgnoreEnd

		return $query_args;
	}

	/**
	 * Get meta query for taxonomy images.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @return array
	 * @since 1.0.0
	 */
	private static function get_meta_query( string $taxonomy ): array {

		// @codingStandardsIgnoreStart
		return array(
			'meta_key'     => $taxonomy . '_image_id',
			'meta_value'   => ' ',
			'meta_compare' => '!=',
		);
		// @codingStandardsIgnoreEnd
	}
}
