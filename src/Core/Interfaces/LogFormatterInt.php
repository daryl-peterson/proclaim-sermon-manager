<?php
/**
 * Log formatter interface.
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPPSM\Interfaces;

use DRPPSM\Logging\LogRecord;

/**
 * Log formatter interface.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface LogFormatterInt {

	/**
	 * Format the log record.
	 *
	 * @param LogRecord $record Record for log.
	 * @return string Formatted string.
	 *
	 * @since 1.0.0
	 */
	public function format( LogRecord $record ): string;
}
