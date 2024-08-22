<?php
/**
 * Log writter abstract class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Logging;

/**
 * Log writter abstract class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
abstract class LogWritterAbs implements LogWritterInt {

	/**
	 * Initialize and register hooks.
	 *
	 * @return LogWritterInt
	 * @since 1.0.0
	 */
	public static function exec(): LogWritterInt {
		$obj = new static();
		$obj->register();
		return $obj;
	}

	/**
	 * Register hooks.
	 *
	 * @return boolean|null True if hooks were registered, otherwise false.
	 * @since 1.0.0
	 */
	abstract public function register(): ?bool;
}
