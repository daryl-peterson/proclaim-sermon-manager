<?php
/**
 * Logging
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPPSM\Logging;

use DRPPSM\Interfaces\LoggerInt;
use DRPPSM\Traits\SingletonTrait;

/**
 * Logging
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class Logger implements LoggerInt {

	use SingletonTrait;

	/**
	 * Write debug log.
	 *
	 * @param mixed $context Context for logging.
	 * @return boolean
	 * @since 1.0.0
	 */
	public static function debug( mixed $context ): bool {
		return self::log( $context, 'debug' );
	}

	/**
	 * Write error log.
	 *
	 * @param mixed $context Context for logging.
	 * @return boolean
	 * @since 1.0.0
	 */
	public static function error( mixed $context ): bool {
		return self::log( $context, 'error' );
	}

	/**
	 * Write info log.
	 *
	 * @param mixed $context Context for logging.
	 * @return boolean
	 * @since 1.0.0
	 */
	public static function info( mixed $context ): bool {
		return self::log( $context, 'info' );
	}

	/**
	 * Write log to file.
	 *
	 * @param mixed  $context Context for logging.
	 * @param string $level Logging level.
	 * @return boolean
	 */
	private static function log( mixed $context, string $level ): bool {
		try {
			$record    = new LogRecord( $context, $level, debug_backtrace( 0, 8 ) );
			$formatter = new LogFormatter();
			$data      = $formatter->format( $record );

			// Add to ensure error log is written
			$file = LogFile::get( $level );

			if ( 'error' === $level ) {
				error_log( $data );
			}

			return file_put_contents( $file, $data, FILE_APPEND );
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			error_log(
				print_r(
					array(
						'MESSAGE' => $th->getMessage(),
						'TRACE'   => $th->getTrace(),
					),
					true
				)
			);

			return false;
			// @codeCoverageIgnoreEnd
		}
	}
}
