<?php
/**
 * Runable interface. Run checks / service ect.
 *
 * @package     Proclain Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Interfaces;

use DRPSermonManager\Exceptions\PluginException;

/**
 * Runable interface. Run checks / service ect.
 *
 * @package     Proclain Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface Runable {

	/**
	 * Run checks / services.
	 *
	 * @since 1.0.0
	 *
	 * @throws PluginException Throws exception if failed to run.
	 */
	public function run(): void;
}
