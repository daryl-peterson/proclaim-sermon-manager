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

use DRPPSM\Constants\PT;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
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


	public static function exec(): Executable {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	public function register(): ?bool {
		if ( shortcode_exists( DRPPSM_SC_LATEST_SERIES ) ) {
			return false;
		}

		add_shortcode( DRPPSM_SC_LATEST_SERIES, array( $this, 'latest_series_image' ) );
		add_shortcode( DRPPSM_SC_LATEST_SERMON, array( $this, 'latest_sermon' ) );

		add_shortcode( DRPPSM_SC_LIST_PODCAST, array( $this, 'podcasts_list' ) );
		add_shortcode( DRPPSM_SC_LIST_SERMONS, array( $this, 'sermons_list' ) );

		add_shortcode( DRPPSM_SC_SERMON_IMAGES, array( $this, 'display_images' ) );
		add_shortcode( DRPPSM_SC_SERMONS, array( $this, 'sermons' ) );
		add_shortcode( DRPPSM_SC_SERMON_SORTING, array( $this, 'sermon_sorting' ) );
		return true;
	}

	public function latest_series_image( array $atts ): void {
		$atts = $this->fix_atts( $atts );

		// Default options.
		$args = array(
			'image_class'      => 'latest-series-image',
			'size'             => 'large',
			'show_title'       => 'yes',
			'title_wrapper'    => 'h3',
			'title_class'      => 'latest-series-title',
			'service_type'     => '',
			'show_description' => 'yes',
			'wrapper_class'    => 'latest-series',
		);

		// Join default and user options.
		$args = shortcode_atts( $args, $atts, 'latest_series' );
	}

	/**
	 * Display latest sermon.
	 *
	 * @param array $atts
	 * @return string
	 * @since 1.0.0
	 */
	public function latest_sermon( array $atts ): string {

		$atts = $this->fix_atts( $atts );

		// order="DESC", orderby="post_modified"
		// Default options.
		$args = array(
			'per_page'   => 10,
			'order'      => 'ASC',
			'orderby'    => 'post_modified',
			'image_size' => 'post-thumbnail',
		);

		// Merge default and user options.
		$args            = shortcode_atts( $args, $atts, DRPPSM_SC_LATEST_SERMON );
		$args['orderby'] = $this->get_order_by( $args );

		// Set query args.
		$query_args = array(
			'post_type'      => PT::SERMON,
			'posts_per_page' => $args['per_page'],
			'order'          => $args['order'],
			'orderby'        => $args['orderby'],
			'post_status'    => 'publish',
		);

		$query = new WP_Query( $query_args );
		Logger::debug( array( 'QUERY' => $query ) );

		// Add query to the args.
		$args['query'] = $query;

		$output = '';

		ob_start();
		if ( $query->have_posts() ) {

			while ( $query->have_posts() ) {
				$query->the_post();
				global $post;

				/**
				 * Filters overrideing of latest sermon output
				 *
				 * @param string $shortcode
				 * @param string $post
				 * @param array $args
				 * @return string
				 * @since 1.0.0
				 */
				$override = apply_filters( DRPPSMF_SC_OUTPUT_OVRD, DRPPSM_SC_LATEST_SERMON, $post, $args );
				if ( $override !== DRPPSM_SC_LATEST_SERMON ) {
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

		Logger::debug( PHP_EOL . $result );

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
	 * Display sermon list.
	 *
	 * @param array $attr
	 * @return void
	 * @since 1.0.0
	 */
	public function sermons_list( array $atts ): void {
		$atts = $this->fix_atts( $atts );

		Logger::debug( 'SERMON LISTING HERE' );
		// echo 'blah';
	}

	/**
	 * Main short code.
	 *
	 * @param array $attr
	 * @return void
	 * @since 1.0.0
	 */
	public function sermons( array $atts ): void {
		$atts = $this->fix_atts( $atts );
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

	public function get_latest_series_image_id( $series = 0 ) {
		if ( 0 !== $series && is_numeric( $series ) ) {
			$series = intval( $series );
		} elseif ( $series instanceof WP_Term ) {
			$series = $series->term_id;
		} else {
			return null;
		}

		$associations = array();

		// @todo Create function
		// $associations = sermon_image_plugin_get_associations();
		$tt_id = absint( $series );

		if ( array_key_exists( $tt_id, $associations ) ) {
			$id = absint( $associations[ $tt_id ] );

			return $id;
		}

		return null;
	}

	private function fix_atts( array $atts ): array {
		foreach ( $atts as &$att ) {
			$att = unquote( $att );
		}
		return $atts;
	}

	private function get_order_by( array $args ): string {
		$orderby = strtolower( $args['orderby'] );

		if ( ! in_array( $orderby, DRPPSM_SERMON_ORDER_BY ) ) {
			$orderby = 'post_date';
		}
		return $orderby;
	}
}
