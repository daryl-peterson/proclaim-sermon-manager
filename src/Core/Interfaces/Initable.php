<?php
/**
 * Initable interface.
 * - Has public static init method.
 * - Returns self.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Interfaces;

/**
 * Initable interface.
 * - Has public static init method.
 * - Returns self.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface Initable {

	/**
	 * Initialize object.
	 *
	 * @since 1.0.0
	 */
	public static function init(): self;
}
