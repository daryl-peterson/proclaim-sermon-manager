<?php
/**
 * Log formatter
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Logging;

use DRPSermonManager\Interfaces\LogFormatterInt;

/**
 * Log formatter
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class LogFormatter implements LogFormatterInt {

	/**
	 * Format the log record.
	 *
	 * @param LogRecord $record Record for log.
	 * @return string Formatted string.
	 *
	 * @since 1.0.0
	 */
	public function format( LogRecord $record ): string {
		$log = '';

		$log .= str_repeat( '*', 80 ) . "\n";
		foreach ( $record as $key => $value ) {
			$log .= $this->str_pad( $key ) . $value . "\n";
		}

		return $log;
	}

	/**
	 * Pad a string for log output.
	 *
	 * @param string $name Header string, class, function, file ect..
	 * @return string String with padding.
	 */
	private function str_pad( string $name ): string {
		return substr( strtoupper( $name ) . str_pad( ' ', 10 ), 0, 10 ) . ': ';
	}
}
