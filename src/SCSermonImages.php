<?php
/**
 * Shortcodes for sermon images.
 *
 * @package     DRPPSM\SCSermonImages
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
 * @package     DRPPSM\SCSermonImages
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
	private string $sc_images;

	/**
	 * Initialize object properties.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function __construct() {
		parent::__construct();
		$this->sc_images = DRPPSM_SC_SERMON_IMAGES;
	}

	/**
	 * Initialize and preform registration hooks if needed.
	 *
	 * @return SCSermonImages
	 * @since 1.0.0
	 */
	public static function exec(): self {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register hooks.
	 *
	 * @return null|bool
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( shortcode_exists( $this->sc_images ) ) {
			return false;
		}
		add_shortcode( $this->sc_images, array( $this, 'show_images' ) );
		return true;
	}

	/**
	 * Show sermon,preacher images. ect.
	 *
	 * @param array $atts Shorcode attributes array.
	 * @return string
	 * @since 1.0.0
	 */
	public function show_images( array $atts ): string {
		$timer     = Timer::get_instance();
		$timer_key = $timer->start( __FUNCTION__, __FILE__ );

		$atts = $this->fix_atts( $atts );
		$args = $this->get_default_args();
		$args = shortcode_atts( $args, $atts, $this->sc_images );

		$tax = $this->get_taxonomy_name( $args['display'] );
		if ( ! $tax ) {
			return '<strong>Error: Invalid "list" parameter.</strong><br> Possible values are: "series", "preachers", "topics" and "books".<br> You entered: "<em>' . $args['display'] . '</em>"';
		}

		$args['display'] = $tax;
		$query_args      = $this->get_query_args( $args );
		$terms           = get_terms( $query_args );

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

		$output = '';
		if ( count( $terms ) > 0 ) {
			ob_start();
			get_partial(
				"taxonomy-{$tax}-grid",
				array(
					'terms'      => $terms,
					'image_size' => $args['image_size'],
					'taxonomy'   => $tax,
					'mkey'       => $this->get_meta_key( $args ),
				)
			);
			$output .= ob_get_clean();
		} else {
			ob_start();
			get_partial( 'no-posts' );
			$output .= ob_get_clean();
		}
		$timer->stop( $timer_key );
		return $output;
	}

	/**
	 * Get default arguments.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function get_default_args(): array {
		return array(
			'display'          => 'series',
			'order'            => 'ASC',
			'orderby'          => 'name',
			'size'             => 'sermon_medium',
			'hide_title'       => false,
			'show_description' => false,
			'image_size'       => 'sermon_medium',
		);
	}

	/**
	 * Get query args.
	 *
	 * @param array $args Arguments array.
	 * @return array
	 * @since 1.0.0
	 */
	private function get_query_args( array $args ): array {

		$query_args = array(
			'taxonomy' => $args['display'],
			'order'    => $args['order'],
			'orderby'  => $args['orderby'],
		);

		switch ( $args['display'] ) {
			case DRPPSM_TAX_SERIES:
				$meta_key = Meta::SERIES_IMAGE_ID;
				break;
			case DRPPSM_TAX_PREACHER:
				$meta_key = Meta::PREACHER_IMAGE_ID;
		}
		// @codingStandardsIgnoreStart
		$query_args['meta_query'][] = array(
			'meta_key'     => $meta_key,
			'meta_value'   => ' ',
			'meta_compare' => '!=',
		);
		// @codingStandardsIgnoreEnd

		return $query_args;
	}

	/**
	 * Get the meta key needed.
	 *
	 * @param array $args Arguments array.
	 * @return null|string
	 * @since 1.0.0
	 */
	private function get_meta_key( array $args ): ?string {
		$meta_key = null;
		switch ( $args['display'] ) {
			case DRPPSM_TAX_SERIES:
				$meta_key = Meta::SERIES_IMAGE_ID;
				break;
			case DRPPSM_TAX_PREACHER:
				$meta_key = Meta::PREACHER_IMAGE_ID;
				break;
			default:
				break;
		}
		return $meta_key;
	}
}
