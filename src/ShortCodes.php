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

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;

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
	 * Taxonomy mapping.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $tax_map;


	/**
	 * Initialize object properties.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->tax_map = DRPPSM_TAX_MAP;
	}

	public static function exec(): Executable {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	public function register(): ?bool {
		if ( shortcode_exists( DRPPSM_SC_LIST_PODCAST ) ) {
			return false;
		}

		add_shortcode( DRPPSM_SC_LIST_PODCAST, array( $this, 'podcasts_list' ) );
		// add_shortcode( DRPPSM_SC_SERMON_IMAGES, array( $this, 'display_images' ) );

		return true;
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
}
