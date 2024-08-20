<?php
/**
 * Logging
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Logging;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Traits\SingletonTrait;
use function DRPPSM\logwritter;

/**
 * Logging
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Logger implements LoggerInt {

	use SingletonTrait;

	const LEVEL_DEBUG   = 'DEBUG';
	const LEVEL_ERROR   = 'ERROR';
	const LEVEL_INFO    = 'INFO';
	const LEVEL_WARNING = 'WARNING';

	/**
	 * Log writter interface.
	 *
	 * @var LogWritterInt
	 */
	private LogWritterInt $writter;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->writter = logwritter();
	}

	/**
	 * Write debug log.
	 *
	 * @param mixed $context Context for logging.
	 * @return boolean
	 * @since 1.0.0
	 */
	public static function debug( mixed $context ): bool {
		$obj = self::get_instance();
		return $obj->log( $context, self::LEVEL_DEBUG );
	}

	/**
	 * Write error log.
	 *
	 * @param mixed $context Context for logging.
	 * @return boolean
	 * @since 1.0.0
	 */
	public static function error( mixed $context ): bool {
		$obj = self::get_instance();
		return $obj->log( $context, self::LEVEL_ERROR );
	}

	/**
	 * Write info log.
	 *
	 * @param mixed $context Context for logging.
	 * @return boolean
	 * @since 1.0.0
	 */
	public static function info( mixed $context ): bool {
		$obj = self::get_instance();
		return $obj->log( $context, self::LEVEL_INFO );
	}

	/**
	 * Write log to file.
	 *
	 * @param mixed  $context Context for logging.
	 * @param string $level Logging level.
	 * @return boolean
	 * @since 1.0.0
	 */
	public function log( mixed $context, string $level ): bool {

		try {
			$result = false;
			$record = new LogRecord( $context, $level, debug_backtrace( 0, 8 ) );

			if ( ! defined( 'WP_DEBUG' ) ) {
				$result = true;
			} elseif ( isset( $this->writter ) ) {
				$result = $this->writter->write( $record );
			}

			if ( self::LEVEL_ERROR === $record->level ) {
				error_log(
					print_r(
						$record,
						true
					)
				);
			}

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

			$result = false;

			// @codeCoverageIgnoreEnd
		}
		return $result;
	}
}
