<?php
/**
 * Fix Log size.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Logging;

use const DRPPSM\LOG_FILE;

/**
 * Fix Log size.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class LogFile {

	/**
	 * Get log file name.
	 *
	 * @param string $level Log leve to use for file.
	 * @return string|null If successfull return string, if not null.
	 * @since 1.0.0
	 */
	public static function get( string $level ): ?string {
		$obj = new static();
		$log = $obj->get_log_file( $level );
		if ( isset( $log ) ) {
			$obj->check_file_size( $log );
		}

		return $log;
	}

	/**
	 * Check the file size.
	 *
	 * @param string $file
	 * @return bool True on success, otherwise false.
	 * @since 1.0.0
	 */
	public function check_file_size( string $file ): bool {
		$result = false;
		try {

			$fs = wp_filesize( $file );
			if ( ! $fs || ( $fs > 5000000 ) ) {
				// $this->truncate( $file );
			}
			$result = true;

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			error_log( print_r( $th, true ) );
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
	public function get_log_file( string $level ): ?string {
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
	public function truncate( string $file ): void {
		// @codeCoverageIgnoreStart
		try {
			$fh = fopen( $file, 'w' );
			if ( $fh ) {
				fclose( $fh );
			}

			// Nothing to do here.
		} catch ( \Throwable $th ) {
			unset( $th );
		}
		// @codeCoverageIgnoreEnd
	}
}
