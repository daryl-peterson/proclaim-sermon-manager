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

use DRPPSM\Constants\Bible;
use DRPPSM\Constants\Meta;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use WP_Error;
use WP_Exception;
use WP_Query;
use WP_Term;

/**
 * Shortcodes class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class ShortCodes implements Executable, Registrable {

	/**
	 * Sermon post type.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $pt_sermon;

	/**
	 * Sermon series latest shortcode.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $sc_series_latest;

	/**
	 * Lastest sermon shortcode
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $sc_sermon_latest;

	/**
	 * Sermons shortcode
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $sc_sermons;

	/**
	 * Terms short code.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $sc_terms;

	/**
	 * Taxonomy mapping.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $tax_map;

	private string $tax_series;

	/**
	 * Initialize object properties.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->pt_sermon        = DRPPSM_PT_SERMON;
		$this->sc_series_latest = DRPPSM_SC_SERIES_LATEST;
		$this->sc_sermon_latest = DRPPSM_SC_SERMON_LATEST;
		$this->sc_sermons       = DRPPSM_SC_SERMONS;
		$this->sc_terms         = DRPPSM_SC_TERMS;
		$this->tax_map          = DRPPSM_TAX_MAP;
		$this->tax_series       = DRPPSM_TAX_SERIES;
	}

	public static function exec(): Executable {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	public function register(): ?bool {
		if ( shortcode_exists( $this->sc_sermon_latest ) ) {
			return false;
		}

		add_shortcode( $this->sc_sermon_latest, array( $this, 'show_sermon_latest' ) );
		add_shortcode( $this->sc_series_latest, array( $this, 'show_series_latest' ) );

		add_shortcode( DRPPSM_SC_LIST_PODCAST, array( $this, 'podcasts_list' ) );
		add_shortcode( $this->sc_terms, array( $this, 'term_list' ) );

		add_shortcode( DRPPSM_SC_SERMON_IMAGES, array( $this, 'display_images' ) );
		add_shortcode( $this->sc_sermons, array( $this, 'show_sermons' ) );
		add_shortcode( DRPPSM_SC_SERMON_SORTING, array( $this, 'sermon_sorting' ) );
		return true;
	}

	/**
	 * Display latest series.
	 *
	 * @param array $atts
	 * @return string
	 * @since 1.0.0
	 *
	 * #### Attr parameter
	 * *defaults shown with ()*
	 *
	 * - **image_class** : Any CSS class you want applied to the image. (drppsm-latest-series-image)
	 * - **size** : Any size registered with add_image_size. The default is "large"
	 * - **show_title** : True or false to show or hide the series title. (true)
	 * - **title_wrapper** : Any of the following: p, h1, h2, h3, h4, h5, h6, div (h3)
	 * - **title_class** : Any CSS class you want applied to the title wrapper. (drppsm-latest-series-title)
	 * - **service_type** : Use the service type slug to show the latest series from a particular service type.
	 * - **show_desc** : True or false to show or hide the series description (false)
	 * - **wrapper_class** Any CSS class you want applied to the div which wraps the output. (drppsm-latest-series)
	 */
	public function show_series_latest( array $atts ): string {
		$atts = $this->fix_atts( $atts );

		// Default options.
		$args = array(
			'image_class'      => 'drppsm-latest-series-image',
			'size'             => 'large',
			'show_title'       => 'yes',
			'title_wrapper'    => 'h3',
			'title_class'      => 'drppsm-latest-series-title',
			'service_type'     => '',
			'show_description' => 'yes',
			'wrapper_class'    => 'drppsm-latest-series',
		);

		// Join default and user options.
		$args = shortcode_atts( $args, $atts, 'latest_series' );

		// Get latest series.
		$latest_series = $this->get_series_latest_with_image( 0, $args['service_type'] );

		// If for some reason we couldn't get latest series.
		if ( null === $latest_series ) {
			return 'No latest series found.';
		} elseif ( false === $latest_series ) {
			return 'No latest series image found.';
		}

		// Image ID.
		$series_image_id = $this->get_series_latest_image_id( $latest_series );

		// If for some reason we couldn't get latest series image.
		if ( null === $series_image_id ) {
			return 'No latest series image found.';
		}

		// Link to series.
		$series_link = get_term_link( $latest_series, 'wpfc_sermon_series' );

		// Image CSS class.
		$image_class = sanitize_html_class( $args['image_class'] );

		// Title wrapper tag name.
		$wrapper_options = array( 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div' );
		if ( ! in_array( sanitize_text_field( $args['title_wrapper'] ), $wrapper_options ) ) {
			$args['title_wrapper'] = 'h3';
		}

		// Title CSS class.
		$title_class = sanitize_html_class( $args['title_class'] );
		$link_open   = '<a href="' . $series_link . '" title="' . $latest_series->name . '" alt="' . $latest_series->name . '">';
		$link_close  = '</a>';

		$image = wp_get_attachment_image( $series_image_id, $args['size'], false, array( 'class' => $image_class ) );

		$title       = '';
		$description = '';
		if ( 'yes' === $args['show_title'] ) {
			$title = $latest_series->name;
			$title = '<' . $args['title_wrapper'] . ' class="' . $title_class . '">' . $title . '</' . $args['title_wrapper'] . '>';
		}
		if ( 'yes' === $args['show_description'] ) {
			$description = '<div class="latest-series-description">' . wpautop( $latest_series->description ) . '</div>';
		}

		$wrapper_class = sanitize_html_class( $args['wrapper_class'] );
		$before        = '<div class="' . $wrapper_class . '">';
		$after         = '</div>';

		$output = $before . $link_open . $image . $title . $link_close . $description . $after;

		return $output;
	}

	/**
	 * Display latest sermon.
	 *
	 * @param array $atts
	 * @return string
	 * @since 1.0.0
	 *
	 *
	 * #### Atts parameter
	 * *defaults shown with ()*
	 * - **filter_by** :Options "series", "preachers", "topics", "books", "service_type"
	 * - **filter_value** : Use the "slug" related to the taxonomy field you want to filter by. ('')
	 * - **image_size** : { sermon_small, sermon_medium, sermon_wide, thumbnail, medium, large, full } ect. (sermon_medium)
	 * - **per_page** : Number of sermons to display. (10)
	 * - **order** : "DESC" for descending; "ASC" for ascending. (DESC)
	 * - **orderby** : Options "date", "id", "none", "title", "name", "rand", "comment_count"
	 *
	 *
	 * ```
	 * // Example using all options.
	 * [drppsm_sermon_latest orderby="date" order="desc" filter_by="series" filter_value="at-the-cross" image_size="sermon_medium" per_page="5"]
	 * ```
	 */
	public function show_sermon_latest( array $atts ): string {

		$atts = $this->fix_atts( $atts );

		// Default options.
		$args = array(
			'filter_by'    => '',
			'filter_value' => '',
			'order'        => 'DESC',
			'orderby'      => '',
			'per_page'     => 10,
			'image_size'   => ImageSize::SERMON_MEDIUM,
		);

		// Merge default and user options.
		$args = shortcode_atts( $args, $atts, $this->sc_sermon_latest );

		// Make sure orderby is correct.
		if ( ! $this->is_valid_orderby( $args ) ) {
			$args['orderby'] = 'post_date';
		}

		// Set query args.
		$query_args = array(
			'post_type'      => $this->pt_sermon,
			'posts_per_page' => $args['per_page'],
			'order'          => $args['order'],
			'orderby'        => $args['orderby'],
			'post_status'    => 'publish',
		);

		$query_args = $this->set_filter( $args, $query_args );
		$query      = new WP_Query( $query_args );

		// Add query to the args.
		$args['query'] = $query;

		$output = '';

		ob_start();
		if ( $query->have_posts() ) {

			while ( $query->have_posts() ) {
				$query->the_post();
				global $post;

				/**
				 * Allows for filtering shortcode output.
				 * - Filters are prefixed with drppsmf_
				 *
				 * @param string $shortcode Shortcode name.
				 * @param string $post Current post.
				 * @param array $args Arguments from shortcode plus defaults.
				 * @return string
				 * @since 1.0.0
				 */
				$override = apply_filters(
					DRPPSMF_SC_OUTPUT_OVRD,
					DRPPSM_SC_SERMON_LATEST,
					$post,
					$args
				);

				if ( $override !== $this->sc_sermon_latest ) {
					$output .= $override;
					continue;
				}
				get_partial( 'content-sermon-archive', $args );

			}

			wp_reset_postdata();
		} else {
			get_partial( 'content-sermon-none' );
		}

		$result = ob_get_clean();

		if ( $output !== '' ) {
			$result = $output;
		}
		return $result;
	}

	/**
	 * Display podcast list.
	 *
	 * @param array $atts
	 * @return void
	 * @since 1.0.0
	 */
	public function podcast_list( array $atts ): void {
		$atts = $this->fix_atts( $atts );
	}

	/**
	 * Display simple unordered term list.
	 *
	 * @param array $atts Attribute list.
	 * @return string
	 * @since 1.0.0
	 *
	 * #### Atts Parameters
	 * - **display** : Options "series", "preachers", "topics", "books", "serives_types".
	 * - **order** : Options "DESC" for descending; "ASC" for ascending.
	 * - **orderby** : Options "name" (default), "id", "count", "slug", "term_group", "none"
	 *
	 * ```
	 * // An example using all three options.
	 * [list_sermons display="preachers" order="DESC" orderby="id"]
	 * ```
	 */
	public function term_list( array $atts ): string {
		$atts = $this->fix_atts( $atts );

		// Default options.
		$defaults = array(
			'display' => 'series',
			'order'   => 'ASC',
			'orderby' => 'name',
		);

		// Join default and user options.
		$args = shortcode_atts( $defaults, $atts, DRPPSM_SC_TERMS );

		// Fix taxonomy
		$args['display'] = $this->convert_taxonomy_name( $args['display'], true );

		$query_args = array(
			'taxonomy' => $args['display'],
			'orderby'  => $args['orderby'],
			'order'    => $args['order'],
		);

		if ( 'date' === $query_args['orderby'] ) {
			$query_args['orderby']        = 'meta_value_num';
			$query_args['meta_key']       = 'sermon_date';
			$query_args['meta_compare']   = '<=';
			$query_args['meta_value_num'] = time();
		}

		// Get items.
		$terms = get_terms( $query_args );

		if ( $terms instanceof WP_Error ) {
			Logger::error(
				array(
					'ERROR' => $terms->get_error_message(),
					$terms->get_error_data(),
				)
			);
			return 'Shortcode Error';
		}

		if ( count( $terms ) > 0 ) {
			// Sort books by order.
			if ( DRPPSM_TAX_BIBLE === $args['display'] && 'book' === $args['orderby'] ) {
				// Book order.
				$books = Bible::BOOKS;

				// Assign every book a number.
				foreach ( $terms as $term ) {
					$ordered_terms[ array_search( $term->name, $books ) ] = $term;
				}

				// Order the numbers (books).
				ksort( $ordered_terms );
				$terms = $ordered_terms;
			}

			$list = '<ul id="list-sermons">';
			foreach ( $terms as $term ) {
				$list .= '<li><a href="' . esc_url( get_term_link( $term, $term->taxonomy ) ) . '" title="' . $term->name . '">' . $term->name . '</a></li>';
			}
			$list .= '</ul>';

			return $list;
		} else {
			// If nothing has been found.
			return 'No ' . $this->convert_taxonomy_name( $args['display'], true ) . ' found.';
		}
	}

	/**
	 * Display sermons.
	 *
	 * @param array $attr
	 * @return void
	 * @since 1.0.0
	 *
	 * #### Atts Parameters
	 * - **per_page** : Define how many sermons to show per page. Overrides the WordPress setting.
	 * - **sermons** : Use comma separated list of individual sermon IDs to show just them.
	 * - **order** : "DESC" for descending; "ASC" for ascending
	 * - **orderby** : Options "date" (default), "id", "none", "title", "name", "rand", "comment_count"
	 * - **filter_by** : Options "series", "preachers", "topics", "books", "service_type". ('')
	 * - **filter_value** : Use the "slug" related to the taxonomy field you want to filter by. ('')
	 * - **hide_pagination** : Set to 1 to hide.
	 * - **image_size** : { sermon_small, sermon_medium, sermon_wide, thumbnail, medium, large, full } any added with add_image_size().
	 * - **year** : Show only sermons created in the specified year.
	 * - **month** : Show only sermons created in the specified month, regardless of year.
	 * - **week** : Show only sermons created in the specified week.
	 * - **day** : Show only sermons created on the specified day.
	 * - **after** : Show only sermons created after the specified date.
	 * - **before** :Show only sermons created before the specified date.
	 */
	public function show_sermons( array $atts ): string {
		global $post_ID;

		$atts = $this->fix_atts( $atts );
		$args = $this->init_sermon_default_args();

		// Merge default and user options.
		$args = shortcode_atts( $args, $atts, 'sermons' );

		$this->fix_sermon_includes_excludes( $args );

		// Set filtering args.
		$filtering_args = $this->init_sermon_filtering( $args );

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

		// Add year month etc filter, adjusted for sermon date.
		if ( 'meta_value_num' === $query_args['orderby'] ) {
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
		Logger::debug(
			array(
				'QUERY ARGS' => $query_args,
				'ARGS'       => $args,
			)
		);

		// Add before and after parameters.
		if ( 'meta_value_num' === $query_args['orderby'] && ( $args['before'] || $args['after'] ) ) {
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

		// Use all meta queries.
		if ( isset( $query_args['meta_query'] ) && count( $query_args['meta_query'] ) > 1 ) {
			$query_args['meta_query']['relation'] = 'AND';
		}

		$query_args = $this->set_filter( $args, $query_args );

		return '';
	}

	/**
	 * Display sermon sorting.
	 *
	 * @param array $attr
	 * @return void
	 * @since 1.0.0
	 */
	public function sermon_sorting( array $atts ): void {
		$atts = $this->fix_atts( $atts );
	}

	/**
	 * Get latest sermon series that has an image.
	 *
	 * @return WP_Term|null|bool Term if found, null if there are no terms, false if there is no term with image.
	 * @since 1.0.0
	 */
	private function get_series_latest_with_image(): WP_Term|null|bool {

		// Get Order from settings
		$default_orderby = Settings::get( Settings::ARCHIVE_ORDER_BY );
		$default_order   = Settings::get( Settings::ARCHIVE_ORDER );

		if ( empty( $default_order ) ) {
			$default_order = '';
		}

		$query_args = array(
			'taxonomy'   => $this->tax_series,
			'hide_empty' => false,
			'order'      => strtoupper( $default_order ),
		);

		switch ( $default_orderby ) {
			case 'date_preached':
				$query_args['meta_query'] = array(
					'orderby'      => 'meta_value_num',
					'meta_key'     => Meta::DATE,
					'meta_value'   => time(),
					'meta_compare' => '<=',
				);
				break;
			default:
				$query_args += array(
					'orderby' => $default_orderby,
				);
		}

		try {
			$series = get_terms( $query_args );
			if ( $series instanceof WP_Error ) {
				return null;
			}
		} catch ( \Throwable | WP_Exception $th ) {
			return null;
		}

		// Fallback to next one until we find the one that has an image.
		foreach ( $series as $item ) {
			if ( $this->get_series_latest_image_id( $item ) ) {
				return $item;
			}
		}

		return is_array( $series ) && count( $series ) > 0 ? false : null;
	}

	/**
	 * Get image id for latest sermon series.
	 *
	 * @param int $series Series term id.
	 * @return WP_Term|int|null
	 * @since 1.0.0
	 */
	private function get_series_latest_image_id( WP_Term|int|null $series = 0 ): ?int {
		if ( 0 !== $series && is_numeric( $series ) ) {
			$series = intval( $series );
		} elseif ( $series instanceof WP_Term ) {
			$series = $series->term_id;
		} else {
			return null;
		}

		$result = get_term_meta( $series, Meta::SERIES_IMAGE_ID, true );
		if ( empty( $result ) ) {
			return null;
		}
		return absint( $result );
	}

	/**
	 * Fix attributes.
	 *
	 * @param array $atts
	 * @return array
	 * @since 1.0.0
	 */
	private function fix_atts( array $atts ): array {
		foreach ( $atts as &$att ) {
			$att = unquote( $att );
		}
		return $atts;
	}

	private function is_valid_orderby( array $args ): bool {
		$orderby = strtolower( $args['orderby'] );

		if ( ! in_array( $orderby, DRPPSM_SERMON_ORDER_BY ) ) {
			return false;
		}
		return true;
	}


	private function init_sermon_default_args() {
		return array(
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
			'hide_filters'       => true,
			'hide_topics'        => '',
			'hide_series'        => '',
			'hide_preachers'     => '',
			'hide_books'         => '',
			'hide_dates'         => '',
			'include'            => '',
			'exclude'            => '',
			'hide_service_types' => '',
		);
	}

	private function init_sermon_filtering( array $args ): array {
		return array(
			'hide_topics'        => $args['hide_topics'],
			'hide_series'        => $args['hide_series'],
			'hide_preachers'     => $args['hide_preachers'],
			'hide_books'         => $args['hide_books'],
			'hide_service_types' => $args['hide_service_types'],
			'hide_dates'         => $args['hide_dates'],
		);
	}


	/**
	 * Explode csv values in args array for include & exclude keys.
	 *
	 * @param array &$args
	 * @return void
	 * @since 1.0.0
	 */
	private function fix_sermon_includes_excludes( array &$args ): void {
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
	private function convert_taxonomy_name( string $name, bool $friendly = false ): string {
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
	 * Set filter if needed.
	 *
	 * @param array $args
	 * @param array $query_args
	 * @return array
	 */
	private function set_filter( array $args, array $query_args ) {

		// Check if there is anything to do !
		if ( ! isset( $args['filter_by'] ) || ! isset( $args['filter_value'] ) ) {
			return $query_args;
		}

		if ( empty( $args['filter_by'] ) || empty( $args['filter_value'] ) ) {
			return $query_args;
		}

		// Term string to array.
		$terms = explode( ',', $args['filter_value'] );
		if ( empty( $terms ) ) {
			return $query_args;
		}

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
			} else {

				// It's a slug so sanitize it.
				$term = sanitize_title( $term );
			}
		}

		$query_args['tax_query'] = array(
			array(
				'taxonomy' => $this->convert_taxonomy_name( $args['filter_by'], true ),
				'field'    => 'slug',
				'terms'    => $terms,
			),
		);

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

		if ( ! empty( $query_args['tax_query'] ) && count( $query_args['tax_query'] ) > 1 && ! empty( $query_args['tax_query']['custom'] ) ) {
			unset( $query_args['tax_query']['custom'] );
		}

		return $query_args;
	}
}
