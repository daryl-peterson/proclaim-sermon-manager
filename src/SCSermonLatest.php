<?php
/**
 * Shortcodes for latest sermon.
 *
 * @package     DRPPSM\SCSermonLatest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;
use WP_Query;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcodes for latest sermon.
 *
 * @package     DRPPSM\SCSermonLatest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SCSermonLatest extends SCBase implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Lastest sermon shortcode
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $sc;

	/**
	 * Initialize object.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function __construct() {
		parent::__construct();
		$this->sc = DRPPSM_SC_SERMON_LATEST;
	}

	/**
	 * Register hooks.
	 *
	 * @return null|bool
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( shortcode_exists( $this->sc ) ) {
			return false;
		}

		add_shortcode( $this->sc, array( $this, 'show_sermon_latest' ) );
		return true;
	}

	/**
	 * Display latest sermon.
	 *
	 * @param array $atts Attributes array.
	 * @return string HTML string.
	 * @since 1.0.0
	 *
	 *
	 * #### Atts parameter
	 * *defaults shown with ()*
	 * - **filter_by** :Options "series", "preachers", "topics", "books", "service_type"
	 * - **filter_value** : Use the "slug" related to the taxonomy field you want to filter by. ('')
	 * - **image_size** : { proclaim_small, proclaim_medium, proclaim_wide, thumbnail, medium, large, full } ect.
	 * - **per_page** : Number of sermons to display. (5)
	 * - **order** : "DESC" for descending; "ASC" for ascending. (DESC)
	 * - **orderby** : Options "date", "id", "none", "title", "name", "rand", "comment_count"
	 *
	 * #### Filters
	 * - **drppsmf_sc_sermon_single_output** : Allows for filtering sermon output.
	 *
	 *
	 * ```
	 * // Example using all options.
	 * [drppsm_sermon_latest orderby="date" order="desc" filter_by="series" filter_value="at-the-cross" image_size="sermon_medium" per_page="5"]
	 * ```
	 */
	public function show_sermon_latest( array $atts ): string {
		global $wp_query;

		if ( ! isset( $wp_query->post->ID ) ) {
			return '';
		}
		$post_id = $wp_query->post->ID;

		$atts = $this->fix_atts( $atts );

		// Default options.
		$args = array(
			'filter_by'    => '',
			'filter_value' => '',
			'order'        => 'DESC',
			'orderby'      => 'post_modified',
			'per_page'     => 5,
			'image_size'   => ImageSize::SERMON_MEDIUM,
		);

		// Merge default and user options.
		$args = shortcode_atts( $args, $atts, $this->sc );

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
		$this->fix_date_orderby( $query_args );

		$query_args = $this->set_filter( $args, $query_args );

		$query = new WP_Query( $query_args );

		Logger::debug(
			array(
				'QUERY'      => $query,
				'QUERY ARGS' => $query_args,
				'ARGS'       => $args,
			)
		);

		// Add query and post_id to the args.
		$args['query']   = $query;
		$args['post_id'] = $post_id;
		$output          = '';

		if ( $query->have_posts() ) {

			ob_start();
			get_partial( 'sc-wrapper-start', $args );
			$output .= ob_get_clean();

			while ( $query->have_posts() ) {
				$query->the_post();
				global $post;

				ob_start();
				get_partial( 'content-sermon-archive', $args );
				$output .= ob_get_clean();

				/**
				 * Filter single sermon output.
				 *
				 * @param string $output Output from sermon rendering.
				 * @param WP_Post $post
				 * @param array $args Array of aguments.
				 *
				 * @category filter
				 * @since 1.0.0
				 */
				$output = apply_filters( 'drppsmf_sc_sermon_single_output', $output, $post, $args );

			}

			wp_reset_postdata();

			ob_start();
			get_partial(
				Template::Pagination,
				array(
					'current' => get_page_number(),
					'total'   => $query->max_num_pages,
					'post_id' => $post_id,
				)
			);
			$output .= ob_get_clean();

			ob_start();
			get_partial( 'sc-wrapper-end', $args );
			$output .= ob_get_clean();

		} else {
			Logger::debug( 'NO POSTS' );
			ob_start();
			get_partial( 'no-posts' );
			$output .= ob_get_clean();
		}

		return $output;
	}
}
