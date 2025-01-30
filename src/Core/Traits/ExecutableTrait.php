<?php
/**
 * Executable trait.
 *
 * @package     DRPPSM\Traits\ExecutableTrait
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Traits;

/**
 * Executable trait.
 *
 * @package     DRPPSM\Traits\ExecutableTrait
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
trait ExecutableTrait {

	/**
	 * Initailize and register hooks.
	 *
	 * @return self
	 * @since 1.0.0
	 */
	public static function exec(): self {
		$obj = new self();

		if ( method_exists( $obj, 'register' ) ) {
			$obj->register();
		}
		return $obj;
	}
}
