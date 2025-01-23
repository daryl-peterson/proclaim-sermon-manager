<?php
/**
 * Taxonomy utilities.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Taxonomy utilities.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxUtils {

	/**
	 * Return registered taxonomies for post type.
	 *
	 * @param string $post_type Post type to get taxomomies for.
	 * @return array Taxonomy name array.
	 * @since 1.0.0
	 */
	public static function get_taxonomies( string $post_type = '' ): array {
		if ( empty( $post_type ) ) {
			$post_type = DRPPSM_PT_SERMON;
		}

		return get_object_taxonomies( $post_type );
	}

	/**
	 * Get taxonomy field
	 *
	 * @param string|integer|\WP_Taxonomy $taxonomy Taxonomy.
	 * @param string                      $field_name Field name to get.
	 * @return string|null String if found, null if not.
	 * @since 1.0.0
	 */
	public static function get_taxonomy_field( string|int|\WP_Taxonomy $taxonomy, string $field_name ): ?string {

		$taxonomy = get_taxonomy( $taxonomy );
		if ( ! $taxonomy instanceof \WP_Taxonomy ) {
			return null;
		}

		if ( isset( $taxonomy->$field_name ) ) {
			return $taxonomy->$field_name;
		}

		if ( isset( $taxonomy->labels->$field_name ) ) {
			return $taxonomy->labels->$field_name;
		}

		return null;
	}

	/**
	 * Get term option for taxonomy.
	 *
	 * @param string $taxonomy Taxonomy name to get terms for.
	 * @param bool   $full If true will return array of term objects.
	 * @return array Terms array.
	 * @since 1.0.0
	 */
	public static function get_term_options( $taxonomy = 'category', bool $full = false ): array {

		$key      = Transient::TERM_OPTS;
		$key_name = $taxonomy;

		if ( $full ) {
			$key_name = $taxonomy . '_full';
		}

		$options = Transient::get( $key );

		if ( ! is_array( $options ) ) {
			$options = array();
		}

		if ( is_array( $options ) && isset( $options[ $key_name ] ) ) {
			return $options[ $key_name ];
		}

		$args['taxonomy']   = $taxonomy;
		$args['hide_empty'] = false;
		$terms              = get_terms( $args );

		/**
		 * @var WP_Error $terms
		 */
		if ( $terms instanceof WP_Error ) {
			Logger::error( $terms->get_error_message() );
			return array();
		}

		// Initialize an empty array.
		$term_options = array();

		if ( ! empty( $terms ) ) {

			foreach ( $terms as $term ) {

				if ( ! $full ) {
					$term_options[ $term->term_id ] = $term->name;
				} else {
					$term_options[] = cast_stdclass( $term );
				}
			}
		}

		$options[ $key_name ] = $term_options;

		Transient::set( $key, $options );
		return $term_options;
	}



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

		$query_args = self::get_query_args_images( $args );
		if ( ! isset( $query_args ) ) {
			return null;
		}

		$list = get_terms( $query_args );
		if ( is_wp_error( $list ) ) {
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
			$query_args = array(
				'hide_empty' => true,
				'meta_key'   => $taxonomy . '_date',
				'fields'     => 'count',
			);
		}

		$term_count = get_terms( $query_args );
		if ( is_wp_error( $term_count ) ) {
			return 0;
		}
		$result = absint( $term_count );
		return $result;
	}


	/**
	 * Get term posts.
	 *
	 * @param array $args Arguments.
	 * @return null|array
	 * @since 1.0.0
	 *
	 * #### args[ post_type ]
	 * - optional - Not required default is drppsm_sermon.
	 *
	 * #### args[ taxonomy ]
	 * - required - Taxonomy name.
	 *
	 * #### args[ numberposts ]
	 * - optional - Number of posts to return. Default is 5.
	 *
	 * #### args[ terms ]
	 * - required - Term ID or array of ids.
	 *
	 * #### args[ meta_query ]
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
	 * Get sermons by term.
	 *
	 * @param string  $taxonomy Taxonomy.
	 * @param integer $term_id Term id.
	 * @param int     $per_page
	 * @return array|null Sermons array or null.
	 * @since 1.0.0
	 */
	public static function get_sermons_by_term( string $taxonomy, int $term_id, int $per_page = 50 ): ?array {

		$post_args = array(
			'post_type'      => DRPPSM_PT_SERMON,
			'showposts'      => -1,
			'posts_per_page' => $per_page,
			/**
			 * 'fields' => 'ids' Returns array of ids.
			 */
			'tax_query'      => array(
				array(
					'taxonomy' => $taxonomy,
					'terms'    => $term_id,
					'field'    => 'term_id',
					'orderby'  => 'term_id',
					'order'    => 'asc',
				),
			),
		);

		// phpcs:disable
		$sermons = query_posts( $post_args );
		// phpcs:enable

		if ( ! is_array( $sermons ) ) {
			return null;
		}
		return $sermons;
	}

	/**
	 * Get meta query for taxonomy images.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @return array
	 * @since 1.0.0
	 */
	private static function get_meta_query_images( string $taxonomy ): array {

		// @codingStandardsIgnoreStart
		return array(
			'meta_key'     => $taxonomy . '_image_id',
			'meta_value'   => ' ',
			'meta_compare' => '!=',
		);
		// @codingStandardsIgnoreEnd
	}

	/**
	 * Get query args.
	 *
	 * @param array $args Query arguments.
	 * @return null|array
	 * @since 1.0.0
	 *
	 * #### args[ taxonomy ]
	 * - required - Taxonomy name.
	 * - drppsm_bible, drppsm_preacher, drppsm_series, drppms_stype, drppsm_topics
	 * - books, preachers, series, service_types, topics
	 *
	 * #### args[ hide_empty ]
	 * - optional - Hide empty terms.
	 *
	 * #### args[ orderby ]
	 * - optional - Order by.
	 *
	 * #### args[ order]
	 * - optional - Order.
	 *
	 * #### args[ number ]
	 * - optional - Number of terms to return.
	 *
	 * #### args[ offset ]
	 * - optional - Offset the query.
	 *
	 * #### args[ fields ]
	 * - optional - Fields to return.
	 *
	 * #### args[ meta_query ]
	 * - optional - Meta query.
	 *
	 * #### args[ tax_query ]
	 * - optional - Tax query.
	 */
	private static function get_query_args_images( array $args ): ?array {

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

		if ( isset( $args['number'] ) ) {
			$query_args['number'] = absint( $args['number'] );
		}

		if ( isset( $args['offset'] ) ) {
			$query_args['offset'] = absint( $args['offset'] );
		}

		if ( key_exists( 'number', $args ) && ! key_exists( 'offset', $args ) ) {
			$query_args['offset'] = 0;
		}

		if ( key_exists( 'fields', $args ) ) {
			$query_args['fields'] = $args['fields'];
		}

		// @codingStandardsIgnoreStart
		if ( isset( $args['meta_query'] ) ) {
			$query_args['meta_query'] = ['relation' => 'AND'];
			$query_args['meta_query'][] = $args['meta_query'];
		}
		$query_args['meta_query'][] = self::get_meta_query_images($args['taxonomy']);


		if (isset($args['tax_query'])) {
			$query_args['tax_query'] = $args['tax_query'];
		}
		Logger::debug($query_args);
		// @codingStandardsIgnoreEnd
		return $query_args;
	}

	/**
	 * Get the actual name of the taxonomy.
	 *
	 * @param string $tax Taxonomy name.
	 * @return null|string
	 * @since 1.0.0
	 *
	 * #### Taxonomy
	 * - drppsm_bible, drppsm_preacher, drppsm_series, drppms_stype, drppsm_topics
	 * - books, preachers, series, service_types, topics
	 */
	public static function get_taxonomy_name( string $taxonomy ): ?string {

		$result  = null;
		$tax_map = DRPPSM_TAX_MAP;
		if ( key_exists( $taxonomy, $tax_map ) ) {
			$result = $tax_map[ $taxonomy ];
		} else {
			$match = array_search( $taxonomy, $tax_map, true );
			if ( $match ) {
				$result = $taxonomy;
			}
		}

		if ( ! $result ) {
			// Lets go ahead and pluralize it.
			if ( substr( $taxonomy, -1 ) !== 's' ) {
				$taxonomy .= 's';
			}

			if ( key_exists( $taxonomy, $tax_map ) ) {
				$result = $tax_map[ $taxonomy ];
			}
		}

		return $result;
	}
}
