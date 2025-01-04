<?php
/**
 * Shortcodes for latest series.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\Meta;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use WP_Error;
use WP_Exception;
use WP_Term;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcodes for latest series.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SCSeriesLatest extends SCBase implements Executable, Registrable {

	/**
	 * Series taxomony.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_series;

	/**
	 * Sermon series latest shortcode.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $sc_series_latest;

	protected function __construct() {
		parent::__construct();

		$this->sc_series_latest = DRPPSM_SC_SERIES_LATEST;
		$this->tax_series       = DRPPSM_TAX_SERIES;
	}

	public static function exec(): Executable {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	public function register(): ?bool {
		if ( shortcode_exists( $this->sc_series_latest ) ) {
			return false;
		}
		add_shortcode( $this->sc_series_latest, array( $this, 'show_series_latest' ) );
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
}
