<?php
/**
 * Series list short code.
 *
 * @package     DRPPSM\ShortCodes\SeriesList
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\ShortCodes;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use DRPPSM\Interfaces\Executable;
use DRPPSM\Traits\ExecutableTrait;

/**
 * Series list short code.
 *
 * @package     DRPPSM\ShortCodes\SeriesList
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Series extends TaxShortcode implements Executable {
	use ExecutableTrait;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->sc   = 'drppsm_series';
		$this->size = 'psm-sermon-medium';
	}
}
