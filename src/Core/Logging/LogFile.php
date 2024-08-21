<?php
/**
 * Write log to file.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Logging;

defined( 'ABSPATH' ) || exit;

use const DRPPSM\LOG_FILE;

/**
 * Write log to file.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class LogFile extends LogWritterAbs implements LogWritterInt {

	/**
	 * Max file size in MB.
	 *
	 * @var integer
	 */
	public int $size;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->size = 5;
	}

	/**
	 * Write log record.
	 *
	 * @param LogRecord $record Log record object.
	 * @return boolean Returns true if log was written, otherwise false.
	 * @since 1.0.0
	 */
	public function write( LogRecord $record ): bool {

		$data = $this->format( $record );
		$file = $this->get_log_file( $record->level );
		$this->check_file_size( $file );
		// phpcs:disable
		if ( 'error' === $record->level ) {
			error_log( $data );
		}
		file_put_contents( $file, $data, FILE_APPEND );
		// phpcs:enable
		return true;
	}

	/**
	 * Check the file size.
	 *
	 * @param string $file File name to check.
	 * @return bool True on success, otherwise false.
	 * @since 1.0.0
	 */
	private function check_file_size( string $file ): bool {
		$result = false;
		try {

			$fs    = wp_filesize( $file );
			$limit = $this->size * ( 1024 * 1024 );
			if ( ! $fs || ( $fs > $limit ) ) {
				$this->truncate( $file );
			}
			$result = true;

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			// phpcs:disable
			error_log( print_r( $th, true ) );
			// phpcs:enable
			$result = false;
			// @codeCoverageIgnoreEnd
		}
		return $result;
	}

	/**
	 * Get the log file.
	 *
	 * @param string $level Log level.
	 * @return string|null String on success, otherwise null.
	 * @since 1.0.0
	 */
	private function get_log_file( string $level ): ?string {
		// @codeCoverageIgnoreStart

		$log_file = LOG_FILE;
		if ( defined( 'WP_DEBUG_LOG' ) ) {
			$log_file = dirname( WP_DEBUG_LOG ) . DIRECTORY_SEPARATOR . LOG_FILE . "-$level.log";
		}

		// @codeCoverageIgnoreEnd
		return $log_file;
	}

	/**
	 * Truncate the log file.
	 *
	 * @param string $file File name.
	 * @return void
	 * @since 1.0.0
	 */
	private function truncate( string $file ): void {
		// @codeCoverageIgnoreStart
		try {
			// phpcs:disable
			$fh = fopen( $file, 'w' );
			if ( $fh ) {
				fclose( $fh );
			}
			// phpcs:enable
		} catch ( \Throwable $th ) {
			unset( $th );
		}
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Format the log record.
	 *
	 * @param LogRecord $record Record for log.
	 * @return string Formatted string.
	 * @since 1.0.0
	 */
	private function format( LogRecord $record ): string {
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
	 * @since 1.0.0
	 */
	private function str_pad( string $name ): string {
		return substr( strtoupper( $name ) . str_pad( ' ', 10 ), 0, 10 ) . ': ';
	}
}
