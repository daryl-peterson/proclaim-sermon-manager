<?php
/**
 * Shortcodes  base class.
 *
 * @package     DRPPSM\SCBase
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use WP_Exception;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcodes  base class.
 *
 * @package     DRPPSM\SCBase
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
	 * @param array $args Arguments array.
	 * @param array $query_args Query arguments array.
	 * @return array
	 * @since 1.0.0
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
						'taxonomy' => $this->convert_taxonomy_name( $args['filter_by'], true ),
						'field'    => 'slug',
						'terms'    => $terms,
					),
				);

			}
		}

		$tax_list = array_values( $this->tax_map );

		foreach ( $tax_list as $filter ) {

			$filter_val = get_query_var( $filter );

			Logger::debug(
				array(
					'QUERY VARS' => get_query_var( $filter ),
					'FILTER'     => $filter,
					'VALUE'      => $filter_val,
					'GET'        => $_GET,
					'POST'       => $_POST,
				)
			);

			if ( ! empty( $filter_val ) ) {
				if ( empty( $query_args['tax_query']['custom'] ) || empty( $query_args['tax_query'] ) ) {
					$query_args['tax_query'] = array();
				}

				$query_args['tax_query'][0][] = array(
					'taxonomy' => $filter,
					'field'    => 'slug',
					'terms'    => sanitize_title_for_query( wp_unslash( $filter_val ) ),
				);

				$query_args['tax_query']['custom'] = true;
			}
		}

		Logger::debug( array( 'QUERY ARGS' => $query_args ) );

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

		// Friendly to unfriendly.
		if ( $friendly ) {

			// Lets go ahead and pluralize it.
			if ( substr( $name, -1 ) !== 's' ) {
				$name .= 's';
			}

			if ( key_exists( $name, $this->tax_map ) ) {
				$result = $this->tax_map[ $name ];
			}

			// Unfriendly to friendly.
		} else {

			$match = array_search( $name, $this->tax_map, true );
			if ( $match ) {
				$result = $match;
			}
		}

		return $result;
	}

	/**
	 * Fix attributes.
	 *
	 * @param array $atts Attributes array.
	 * @return array
	 * @since 1.0.0
	 */
	protected function fix_atts( array $atts ): array {
		foreach ( $atts as &$att ) {
			$att = unquote( $att );
		}
		return $atts;
	}

	/**
	 * Check if the order by is valid.
	 *
	 * @param array $args Attributes array.
	 * @return bool
	 * @since 1.0.0
	 */
	protected function is_valid_orderby( array $args ): bool {
		$orderby = strtolower( $args['orderby'] );

		if ( ! in_array( $orderby, DRPPSM_SERMON_ORDER_BY, true ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Get the actual name of the taxonomy.
	 *
	 * @param string $tax Taxonomy could be series this would convert to drppsm_series.
	 * @return null|string
	 * @since 1.0.0
	 */
	protected function get_taxonomy_name( string $tax ): ?string {

		$result = null;
		if ( key_exists( $tax, $this->tax_map ) ) {
			$result = $this->tax_map[ $tax ];
		} else {
			$match = array_search( $tax, $this->tax_map, true );
			if ( $match ) {
				$result = $tax;
			}
		}

		if ( ! $result ) {
			// Lets go ahead and pluralize it.
			if ( substr( $tax, -1 ) !== 's' ) {
				$tax .= 's';
			}

			if ( key_exists( $tax, $this->tax_map ) ) {
				$result = $this->tax_map[ $tax ];
			}
		}

		return $result;
	}

	/**
	 * Fix orderby.
	 *
	 * @param array &$query_args
	 * @return void
	 * @throws WP_Exception
	 */
	protected function fix_date_orderby( array &$query_args ) {

		$orderby    = $query_args['orderby'];
		$meta_query = false;

		if ( 'date' === $query_args['orderby'] ) {
			$setting         = Settings::get( Settings::ARCHIVE_ORDER_BY );
			$args['orderby'] = $setting;
		}

		$fix = array( 'preached', 'date_preached', SermonMeta::DATE );
		if ( in_array( $orderby, $fix, true ) ) {
			$meta_query = true;
		}

		if ( $meta_query ) {
			$query_args['meta_query'] = array(
				'orderby'      => 'meta_value_num',
				'meta_key'     => SermonMeta::DATE,
				'meta_value'   => time(),
				'meta_compare' => '<=',
			);
			unset( $query_args['orderby'] );
		}
	}
}
