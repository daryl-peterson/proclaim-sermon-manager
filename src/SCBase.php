<?php
/**
 * Shortcodes class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

/**
 * Class description
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SCBase {

	/**
	 * Sermon post type.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected string $pt_sermon;


	/**
	 * Taxonomy mapping.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected array $tax_map;

	/**
	 * Initialize object properties.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->pt_sermon = DRPPSM_PT_SERMON;
		$this->tax_map   = DRPPSM_TAX_MAP;
	}

	/**
	 * Set filter if needed.
	 *
	 * @param array $args
	 * @param array $query_args
	 * @return array
	 */
	protected function set_filter( array $args, array $query_args ) {

		if ( $args['filter_by'] && $args['filter_value'] ) {
			// Term string to array.
			$terms = explode( ',', $args['filter_value'] );

			if ( ! empty( $terms ) ) {
				$field = 'slug';

				if ( is_numeric( $terms[0] ) ) {
					$field = 'id';
				}

				foreach ( $terms as &$term ) {
					$term = trim( $term );

					if ( 'id' === $field ) {
						// Remove if it's not an ID.
						if ( ! is_numeric( $term ) ) {
							unset( $term );
							continue;
						}

						// Convert to int.
						$term = intval( $term );
					}
				}

				$query_args['tax_query'] = array(
					array(
						'taxonomy' => $this->convert_taxonomy_name( $args['filter_by'], false ),
						'field'    => 'slug',
						'terms'    => $terms,
					),
				);
			}
		}

		Logger::debug( $query_args );

		$tax_list = array_values( $this->tax_map );
		foreach ( $tax_list as $filter ) {
			if ( ! empty( $_GET[ $filter ] ) ) {
				if ( empty( $query_args['tax_query']['custom'] ) || empty( $query_args['tax_query'] ) ) {
					$query_args['tax_query'] = array();
				}

				$query_args['tax_query'][0][] = array(
					'taxonomy' => $filter,
					'field'    => 'slug',
					'terms'    => sanitize_title_for_query( $_GET[ $filter ] ),
				);

				$query_args['tax_query']['custom'] = true;
			}

			if ( ! empty( $_POST[ $filter ] ) ) {
				if ( empty( $query_args['tax_query']['custom'] ) || empty( $query_args['tax_query'] ) ) {
					$query_args['tax_query'] = array();
				}

				$query_args['tax_query'][0][] = array(
					'taxonomy' => $filter,
					'field'    => 'slug',
					'terms'    => sanitize_title_for_query( $_POST[ $filter ] ),
				);

				$query_args['tax_query']['custom'] = true;
			}
		}

		Logger::debug( $query_args );

		if ( ! empty( $query_args['tax_query'] ) && count( $query_args['tax_query'] ) > 1 && ! empty( $query_args['tax_query']['custom'] ) ) {
			unset( $query_args['tax_query']['custom'] );
		}

		return $query_args;
	}

	/**
	 * Convert between friendly and unfriendly taxomomy names.
	 *
	 * @param string $name Search for string.
	 * @param bool   $friendly If true will convert friendly => unfriendly else unfriendly => friendly\
	 *               In the event of no conversion orginal $name is returned.
	 *
	 * @return string The converted taxonomy or orginal supplied argument.
	 * @since 1.0.0
	 *
	 * ```
	 * // Example friendly to unfriendly.
	 * $this->convert_taxonomy_name('series',true); # returns drppms_series
	 * ```
	 */
	protected function convert_taxonomy_name( string $name, bool $friendly = false ): string {
		$result = $name;

		// friendly => unfriendly
		if ( $friendly ) {

			// Lets go ahead and pluralize it.
			if ( substr( $name, -1 ) !== 's' ) {
				$name .= 's';
			}

			if ( key_exists( $name, $this->tax_map ) ) {
				$result = $this->tax_map[ $name ];
			}

			// unfriendly => friendly
		} else {

			$match = array_search( $name, $this->tax_map );
			if ( $match ) {
				$result = $match;
			}
		}

		return $result;
	}

	/**
	 * Fix attributes.
	 *
	 * @param array $atts
	 * @return array
	 * @since 1.0.0
	 */
	protected function fix_atts( array $atts ): array {
		foreach ( $atts as &$att ) {
			$att = unquote( $att );
		}
		return $atts;
	}

	protected function is_valid_orderby( array $args ): bool {
		$orderby = strtolower( $args['orderby'] );

		if ( ! in_array( $orderby, DRPPSM_SERMON_ORDER_BY ) ) {
			return false;
		}
		return true;
	}
}
