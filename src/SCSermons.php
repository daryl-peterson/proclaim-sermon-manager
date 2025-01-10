<?php
/**
 * Sermon shortcode.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\Meta;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use WP_Query;

/**
 * Sermon shortcode.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SCSermons extends SCBase implements Executable, Registrable {

	/**
	 * Sermons shortcode
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $sc_sermons;

	protected function __construct() {
		parent::__construct();
		$this->sc_sermons = DRPPSM_SC_SERMONS;
	}

	public static function exec(): Executable {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	public function register(): ?bool {
		if ( shortcode_exists( $this->sc_sermons ) ) {
			return false;
		}
		add_shortcode( $this->sc_sermons, array( $this, 'show_sermons' ) );
		return true;
	}

	/**
	 * Display sermons.
	 *
	 * @param array $attr
	 * @return void
	 * @since 1.0.0
	 *
	 * #### Atts Parameters
	 * - **per_page** Define how many sermons to show per page. Overrides the WordPress setting.
	 * - **sermons** Use comma separated list of individual sermon IDs to show just them.
	 * - **order** "DESC" for descending; "ASC" for ascending
	 * - **orderby** Options "date" (default), "id", "none", "title", "name", "rand", "comment_count"
	 * - **filter_by** Options "series", "preachers", "topics", "books", "service_type". ('')
	 * - **filter_value** Use the "slug" related to the taxonomy field you want to filter by. ('')
	 * - **disable_pagination**  Set to 1 to hide pagination.
	 * - **image_size** : { proclaim_small, proclaim_medium, proclaim_wide, thumbnail, medium, large, full } ect.
	 * - **year** Show only sermons created in the specified year.
	 * - **month** Show only sermons created in the specified month, regardless of year.
	 * - **week** Show only sermons created in the specified week.
	 * - **day** Show only sermons created on the specified day.
	 * - **after**  Show only sermons created after the specified date.
	 * - **before** Show only sermons created before the specified date.
	 *
	 * #### These can be set from settings
	 * - **hide_filters** Hide sermon filters. (false)
	 * - **hide_topics** Hide topics filter. ('')
	 * - **hide_series** Hide series filter. ('')
	 * - **hide_service_types** Hide service types filter. ('')
	 */
	public function show_sermons( array $atts ): string {
		global $wp_query;
		$post_id = $wp_query->post->ID;

		/**
		 * Allows for short code attribute filtering. A bit redundant but here it is.
		 * - Filters are prefixed with drppsmf_
		 *
		 * @param array $atts
		 *
		 * @category filter
		 * @since 1.0.0
		 */
		$atts = apply_filters( 'drppsmf_sc_sermon_atts', $atts );

		// Fix atts and get defaults.
		$atts = $this->fix_atts( $atts );
		$args = $this->get_sermon_default_args();

		// Merge default and user options.
		$args = shortcode_atts( $args, $atts, $this->sc_sermons );

		$this->set_includes_excludes( $args );

		// Set filtering args.
		$filtering_args = $this->get_sermon_filtering_defaults( $args );

		// Set query args.
		$query_args = array(
			'post_type'      => $this->pt_sermon,
			'posts_per_page' => $args['per_page'],
			'order'          => $args['order'],
			'paged'          => get_query_var( 'paged' ),
		);

		if ( ! $this->is_valid_orderby( $args ) ) {
			$args['orderby'] = 'date_preached';
		}
		if ( 'date' === $args['orderby'] ) {
			$args['orderby'] = 'date' === get_archive_order_by( 'date' ) ? 'date_published' : 'date_preached';
		}

		$this->set_order_by( $args, $query_args );

		// Add year month etc filter, adjusted for sermon date.
		$this->set_date_ordering( $args, $query_args );

		// Add before and after parameters.
		$this->set_before_after( $args, $query_args );

		// Use all meta queries.
		if ( isset( $query_args['meta_query'] ) && count( $query_args['meta_query'] ) > 1 ) {
			$query_args['meta_query']['relation'] = 'AND';
		}

		$query_args = $this->set_filter( $args, $query_args );
		$query      = new WP_Query( $query_args );

		// Add query to the args.
		$args['query']   = $query;
		$args['post_id'] = $post_id;

		$output = '';

		if ( $query->have_posts() ) {

			$output .= sermon_sorting( $filtering_args );

			while ( $query->have_posts() ) {
				$query->the_post();
				global $post;

				// Check includes and excludes.
				if ( $args['include'] || $args['exclude'] ) {
					if ( ! in_array( $post->ID, $args['include'] ) ) {
						continue;
					}

					if ( ! in_array( $post->ID, $args['exclude'] ) ) {
						continue;
					}
				}

				ob_start();
				get_partial( 'content-sermon-archive', $args );
				$output .= ob_get_clean();

				/**
				 * Filter single sermon output.
				 * - Filters shoud be prefixed with drppsmf_
				 *
				 * @param string $output Output from sermon rendering.
				 * @param WP_Post $post
				 * @param array $args Array of aguments.
				 * @category filter
				 * @since 1.0.0
				 */
				$output = apply_filters( 'drppsmf_sc_sermon_single_output', $output, $post, $args );
			}
			ob_start();
			get_partial( 'sermon-pagination', $args );
			$output .= ob_get_clean();

			wp_reset_postdata();
		} else {
			ob_start();
			get_partial( 'content-sermon-none' );
			$output .= ob_get_clean();
		}

		return $output;
	}



	/**
	 * Set order by parameter.
	 *
	 * @param array &$args
	 * @param array &$query_args
	 * @return void
	 * @since 1.0.0
	 */
	private function set_order_by( array &$args, array &$query_args ): void {
		switch ( $args['orderby'] ) {
			case 'preached':
			case 'date_preached':
			case '':
				$args['orderby'] = 'meta_value_num';

				$query_args['meta_query'] = array(
					array(
						'key'     => Meta::DATE,
						'value'   => time(),
						'type'    => 'numeric',
						'compare' => '<=',
					),
				);
				break;
			case 'published':
			case 'date_published':
				$args['orderby'] = 'date';
				break;
			case 'id':
				$args['orderby'] = 'ID';
				break;
		}

		$query_args['orderby'] = $args['orderby'];
	}

	/**
	 * Fix date ordering.
	 *
	 * @param array $args
	 * @param array $query_args
	 * @return void
	 * @since 1.0.0
	 */
	private function set_date_ordering( array $args, array &$query_args ) {
		if ( 'meta_value_num' !== $query_args['orderby'] ) {
			return;
		}
		// Add year month etc filter, adjusted for sermon date.
		$date_args = array(
			'year',
			'month',
		);

		foreach ( $date_args as $date_arg ) {
			if ( ! isset( $args[ $date_arg ] ) || ! $args[ $date_arg ] ) {
				continue;
			}

			// Reset the query.
			$query_args['meta_query'] = array();

			switch ( $date_arg ) {
				case 'year':
					$year = $args['year'];

					$query_args['meta_query'][] = array(
						'key'     => 'sermon_date',
						'value'   => array(
							strtotime( $year . '-01-01' ),
							strtotime( $year . '-12-31' ),
						),
						'compare' => 'BETWEEN',
					);
					break;
				case 'month':
					$year  = $args['year'] ?: date( 'Y' );
					$month = intval( $args['month'] ) ?: date( 'm' );

					$query_args['meta_query'][] = array(
						'key'     => 'sermon_date',
						'value'   => array(
							strtotime( $year . '-' . $args['month'] . '-' . '01' ),
							strtotime( $year . '-' . $month . '-' . cal_days_in_month( CAL_GREGORIAN, $month, $year ) ),
						),
						'compare' => 'BETWEEN',
					);
					break;
			}
		}
	}

	/**
	 * Set after and before arguments.
	 *
	 * @param array $args
	 * @param array &$query_args
	 * @return void
	 * @since 1.0.0
	 */
	private function set_before_after( array $args, array &$query_args ): void {
		if ( 'meta_value_num' !== $query_args['orderby'] && ( ! $args['before'] || ! $args['after'] ) ) {
			return;
		}
		// Add before and after parameters.

		if ( ! isset( $query_args['meta_query'] ) ) {
			$query_args['meta_query'] = array();
		}

		if ( $args['before'] ) {
			$before = strtotime( $args['before'] );

			$query_args['meta_query'][] = array(
				'key'     => 'sermon_date',
				'value'   => $before,
				'compare' => '<=',
			);
		}

		if ( $args['after'] ) {
			$after = strtotime( $args['after'] );

			$query_args['meta_query'][] = array(
				'key'     => 'sermon_date',
				'value'   => $after,
				'compare' => '>=',
			);
		}
	}

	/**
	 * Explode csv values in args array for include & exclude keys.
	 *
	 * @param array &$args
	 * @return void
	 * @since 1.0.0
	 */
	private function set_includes_excludes( array &$args ): void {
		$search = array( 'include', 'exclude' );

		foreach ( $search as $key ) {
			$data   = explode( ',', $args[ $key ] );
			$return = array();
			foreach ( $data as $value ) {
				if ( ! is_numeric( trim( $value ) ) ) {
					continue;
				}
				$return[] = intval( trim( $value ) );
			}
			$args[ $key ] = $return;
		}
	}

	/**
	 * Get sermon default arguments.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function get_sermon_default_args(): array {
		$defaults = array(
			'per_page'           => get_option( 'posts_per_page' ) ?: 10,
			'sermons'            => false, // Show only sermon IDs that are set here.
			'order'              => get_archive_order(),
			'orderby'            => get_archive_order_by(),
			'disable_pagination' => 0,
			'image_size'         => 'post-thumbnail',
			'filter_by'          => '',
			'filter_value'       => '',
			'year'               => '',
			'month'              => '',
			'after'              => '',
			'before'             => '',
			'include'            => '',
			'exclude'            => '',
		);

		$filters   = get_visibility_settings();
		$defaults += $filters;
		return $defaults;
	}

	/**
	 * Get sermon filtering defaults.
	 *
	 * @param array $args
	 * @return array
	 * @since 1.0.0
	 */
	private function get_sermon_filtering_defaults( array $args ): array {
		return array(
			'hide_filters'       => $args['hide_filters'],
			'hide_topics'        => $args['hide_topics'],
			'hide_series'        => $args['hide_series'],
			'hide_preachers'     => $args['hide_preachers'],
			'hide_books'         => $args['hide_books'],
			'hide_service_types' => $args['hide_service_types'],
		);
	}
}
