<?php
/**
 * Logs fatal error and deactivate plugin.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Logging\Logger;

/**
 * Logs fatal error and deactivate plugin.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class FatalError {

	/**
	 * Set error message and deactivate.
	 *
	 * @param \Throwable $th Throwable.
	 * @return void
	 */
	public static function set( \Throwable $th ) {
		Logger::error(
			array(
				'MESSAGE' => $th->getMessage(),
				'TRACE'   => $th->getTrace(),
			)
		);
		Deactivator::init()->run();
	}
}
