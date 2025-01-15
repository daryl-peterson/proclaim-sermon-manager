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
	 * @param array $args Arguments.
	 * @return null|array
	 *
	 * #### args[taxonomy]
	 * - drppsm_bible
	 * - drppsm_preacher
	 * - drppsm_series
	 * - drppms_stype
	 * - drppsm_topics
	 * - books
	 * - preachers
	 * - series
	 * - service_types
	 * - topics
	 *
	 * #### args[orderby]
	 * - name
	 * - slug
	 * - term_group
	 * - term_id
	 * - id
	 * - description
	 * - parent
	 * - count
	 * - term_taxonomy_id
	 *
	 * #### args[order]
	 * - ASC
	 * - DESC
	 *
	 * #### args[number]
	 * - int Number of terms to return.
	 *
	 * #### args[offset]
	 * - int Offset the query.
	 */
	public static function get_terms_with_images( array $args ): ?array {

		$query_args = self::get_query_args( $args );
		if ( ! isset( $query_args ) ) {
			return null;
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
	 * @param bool   $images Must have images.
	 * @return int
	 * @since 1.0.0
	 */
	public static function get_term_count( string $taxonomy, bool $images = false ): int {
		$query_args = array(
			'taxonomy' => $taxonomy,
			'fields'   => 'count',
		);

		if ( $images ) {
			$query_args['meta_query'] = self::get_meta_query( $taxonomy );
		}

		$term_count = get_terms( $query_args );
		if ( $term_count instanceof WP_Error ) {
			return 0;
		}
		return absint( $term_count );
	}


	/**
	 * Get term posts.
	 *
	 * @param array $args Arguments.
	 * @return null|array
	 * @since 1.0.0
	 *
	 * #### args[post_type]
	 * - optional - Not required default is drppsm_sermon.
	 *
	 * #### args[taxonomy]
	 * - required - Taxonomy name.
	 *
	 * #### args[numberposts]
	 * - optional - Number of posts to return. Default is 5.
	 *
	 * #### args[terms]
	 * - required - Term ID or array of ids.
	 *
	 * #### args[meta_query]
	 * - optional - Meta query.
	 */
	public static function get_term_posts( array $args ): ?array {
		$query_args['post_type'] = DRPPSM_PT_SERMON;
		if ( isset( $args['post_type'] ) ) {
			$query_args['post_type'] = $args['post_type'];
		}

		if ( isset( $args['numberposts'] ) ) {
			$query_args['numberposts'] = $args['numberposts'];
		}

		if ( ! isset( $args['taxonomy'] ) || ! isset( $args['terms'] ) ) {
			return null;
		}

		if ( isset( $args['taxonomy'] ) && isset( $args['terms'] ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => $args['taxonomy'],
					'field'    => 'term_id',
					'terms'    => $args['terms'],
				),
			);
		}

		if ( isset( $args['meta_query'] ) ) {
			$query_args['meta_query'] = $args['meta_query'];
		}

		$posts = get_posts( $query_args );
		if ( $posts instanceof WP_Error ) {
			return null;
		}
		return $posts;
	}


	/**
	 * Get the actual name of the taxonomy.
	 *
	 * @param string $tax Taxonomy could be series this would convert to drppsm_series.
	 * @return null|string
	 * @since 1.0.0
	 */
	public static function get_taxonomy_name( string $tax ): ?string {

		$result  = null;
		$tax_map = DRPPSM_TAX_MAP;
		if ( key_exists( $tax, $tax_map ) ) {
			$result = $tax_map[ $tax ];
		} else {
			$match = array_search( $tax, $tax_map, true );
			if ( $match ) {
				$result = $tax;
			}
		}

		if ( ! $result ) {
			// Lets go ahead and pluralize it.
			if ( substr( $tax, -1 ) !== 's' ) {
				$tax .= 's';
			}

			if ( key_exists( $tax, $tax_map ) ) {
				$result = $tax_map[ $tax ];
			}
		}

		return $result;
	}


	/**
	 * Get query args.
	 *
	 * @param array $args Query arguments.
	 * @return null|array
	 * @since 1.0.0
	 */
	private static function get_query_args( array $args ): ?array {

		if ( ! isset( $args['taxonomy'] ) ) {
			return null;
		}

		if ( ! is_array( $args['taxonomy'] ) ) {
			$args['taxonomy'] = self::get_taxonomy_name( $args['taxonomy'] );
		}

		$query_args['taxonomy'] = $args['taxonomy'];

		if ( isset( $args['hide_empty'] ) ) {
			$query_args['hide_empty'] = $args['hide_empty'];
		}

		if ( isset( $args['orderby'] ) && isset( $args['order'] ) ) {
			$query_args['order']   = $args['order'];
			$query_args['orderby'] = $args['orderby'];
		}

		if ( isset( $args['number'] ) && is_int( $args['number'] ) ) {
			$query_args['number'] = $args['number'];
		}

		if ( isset( $args['offset'] ) && is_int( $args['offset'] ) ) {
			$query_args['offset'] = $args['offset'];
		}

		if ( key_exists( 'number', $args ) && ! key_exists( 'offset', $args ) ) {
			$query_args['offset'] = 0;
		}

		if ( key_exists( 'fields', $args ) ) {
			$query_args['fields'] = $args['fields'];
		}

		// @codingStandardsIgnoreStart
		$query_args['meta_query'][] = self::get_meta_query($args['taxonomy']);
		if ( isset( $args['meta_query'] ) ) {
			$query_args['meta_query'][] = $args['meta_query'];
		}

		if (isset($args['tax_query'])) {
			$query_args['tax_query'] = $args['tax_query'];
		}
		// @codingStandardsIgnoreEnd
		Logger::debug( array( 'QUERY_ARGS' => $query_args ) );

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
