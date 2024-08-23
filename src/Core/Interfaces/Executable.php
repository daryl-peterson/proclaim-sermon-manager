<?php
/**
 * Excecution interface.
 * Preforms init and register.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Interfaces;

/**
 * Excecution interface.
 * Preforms init and register.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
interface Executable {

	/**
	 * Should preform init and register functions.
	 *
	 * @return self
	 * @since 1.0.0
	 */
	public static function exec(): self;
}
