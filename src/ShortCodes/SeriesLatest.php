<?php
/**
 * Shortcodes for latest series.
 *
 * @package     DRPPMS\ShortCodes\SeriesLatest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 *
 * @todo Add layout options.
 */

namespace DRPPSM\ShortCodes;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use DRPPSM\Interfaces\Executable;
use DRPPSM\Traits\ExecutableTrait;

/**
 * Shortcodes for latest series.
 *
 * @package     DRPPMS\ShortCodes\SeriesLatest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SeriesLatest extends ShortCode implements Executable {
	use ExecutableTrait;

	/**
	 * Initialize object.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function __construct() {

		$this->sc  = 'drppsm_series_latest';
		$this->tax = DRPPSM_TAX_SERIES;
	}

	/**
	 * Display shortcode output.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 * @since 1.0.0
	 */
	public function show( array $atts ): string {
		$atts = $this->fix_atts( $atts );

		$output = '';
		return $output;
	}
}
