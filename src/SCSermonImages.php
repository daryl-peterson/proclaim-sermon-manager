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

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;

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
		$atts = $this->fix_atts( $atts );
		$args = $this->get_default_args();

		// Join default and user options.
		$args = shortcode_atts( $args, $atts, $this->sc );

		$tax = $this->get_taxonomy_name( $args['display'] );
		if ( ! $tax ) {
			return '<strong>Error: Invalid "list" parameter.</strong><br> Possible values are: "series", "preachers", "topics" and "books".<br> You entered: "<em>' . $args['display'] . '</em>"';
		}
		$args['display'] = $tax;

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
}
