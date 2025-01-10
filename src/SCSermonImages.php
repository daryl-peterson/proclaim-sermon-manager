<?php

/**
 * Shortcodes for sermon images.
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
use WP_Error;

/**
 * Shortcodes for sermon images.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SCSermonImages extends SCBase implements Executable, Registrable {

	/**
	 * Shortcode name.
	 *
	 * @var string
	 */
	private string $sc;

	protected function __construct() {
		parent::__construct();
		$this->sc = DRPPSM_SC_SERMON_IMAGES;
	}

	public static function exec(): self {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	public function register(): ?bool {
		if ( shortcode_exists( $this->sc ) ) {
			return false;
		}
		add_shortcode( $this->sc, array( $this, 'show_images' ) );
		return true;
	}

	/**
	 * Show sermon,preacher images. ect.
	 *
	 * @param array $atts
	 * @return string
	 * @since 1.0.0
	 */
	public function show_images( array $atts ): string {
		$timer     = Timer::get_instance();
		$timer_key = $timer->start( __FUNCTION__, __FILE__ );

		$atts = $this->fix_atts( $atts );
		$args = $this->get_default_args();

		// Join default and user options.
		$args = shortcode_atts( $args, $atts, $this->sc );

		$tax = $this->get_taxonomy_name( $args['display'] );
		if ( ! $tax ) {
			return '<strong>Error: Invalid "list" parameter.</strong><br> Possible values are: "series", "preachers", "topics" and "books".<br> You entered: "<em>' . $args['display'] . '</em>"';
		}
		$args['display'] = $tax;

		$query_args = $this->get_query_args( $args );

		// Get items.
		$terms = get_terms( $query_args );

		if ( $terms instanceof WP_Error ) {
			Logger::error(
				array(
					'ERROR' => $terms->get_error_message(),
					$terms->get_error_data(),
				)
			);
			$timer->stop( $timer_key );
			return 'Shortcode Error';
		}

		if ( count( $terms ) > 0 ) {
			Logger::debug( $terms );

		} else {

		}

		return '';
	}

	private function get_default_args(): array {
		return array(
			'display'          => 'series',
			'order'            => 'ASC',
			'orderby'          => 'name',
			'size'             => 'sermon_medium',
			'hide_title'       => false,
			'show_description' => false,
		);
	}

	private function get_query_args( array $args ): array {
		$query_args = array(
			'taxonomy' => $args['display'],
			'orderby'  => $args['orderby'],
			'order'    => $args['order'],
		);

		if ( 'date' === $query_args['orderby'] ) {
			$query_args['orderby']        = 'meta_value_num';
			$query_args['meta_key']       = Meta::DATE;
			$query_args['meta_compare']   = '<=';
			$query_args['meta_value_num'] = time();
		}
		return $query_args;
	}
}
