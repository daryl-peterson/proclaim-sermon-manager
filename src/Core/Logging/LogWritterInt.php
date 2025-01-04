<?php
/**
 * Log writter interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Logging;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;

/**
 * Log writter interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
interface LogWritterInt extends Executable, Registrable {


	/**
	 * Write log record.
	 *
	 * @param LogRecord $record Log record object.
	 * @return boolean Return true if log was written, otherwise false.
	 * @since 1.0.0
	 */
	public function write( LogRecord $record ): bool;

	/**
	 * Truncate data.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function truncate(): bool;

		/**
		 * Display debug log.
		 *
		 * @return void
		 * @since 1.0.0
		 */
	public function show(): void;
}
