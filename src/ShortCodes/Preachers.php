<?php
/**
 * Preachers short code.
 *
 * @package     DRPPSM\ShortCodes\Preachers
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
use function DRPPSM\get_tax_image_size;

/**
 * Preachers short code.
 *
 * @package     DRPPSM\ShortCodes\Preachers
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Preachers extends TaxShortcode implements Executable {

	use ExecutableTrait;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->sc   = DRPPSM_TAX_PREACHER;
		$this->size = get_tax_image_size( 'full', $this->sc );
	}
}
