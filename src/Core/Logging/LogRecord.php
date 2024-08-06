<?php
/**
 * Log record format
 *
 * @package     Proclain Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Logging;

use DRPSermonManager\Helper;
/**
 * Log record format
 *
 * @package     Proclain Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class LogRecord {

	/**
	 * Date for log
	 *
	 * @var string
	 */
	public string $date;

	/**
	 * Log leve.
	 *
	 * @var string
	 */
	public string $level;

	/**
	 * Class from trace.
	 *
	 * @var string
	 */
	public string $class;

	/**
	 * Function from trace.
	 *
	 * @var string
	 */
	public string $function;

	/**
	 * Line from trace.
	 *
	 * @var string
	 */
	public string $line;

	/**
	 * File from trace.
	 *
	 * @var string
	 */
	public string $file;

	/**
	 * Log context
	 *
	 * @var string
	 */
	public string $context;

	/**
	 * Initialize object
	 *
	 * @param mixed  $context Log context.
	 * @param string $level Log level.
	 * @param array  $trace Log trace.
	 */
	public function __construct( mixed $context, string $level, array $trace ) {
		$datetime       = new \DateTime( 'now', wp_timezone() );
		$dt             = $datetime->format( 'm-d-Y H:i:s.u e' );
		$this->date     = $dt;
		$this->level    = strtoupper( $level );
		$this->class    = '';
		$this->function = '';
		$this->line     = '';
		$this->file     = '';
		$this->context  = $this->fix_context( $context );

		$start = $this->get_start_pos( $trace );

		if ( isset( $start ) ) {
			$this->get_trace_info( $trace, $start );
		}

		$dir        = Helper::get_plugin_dir();
		$this->file = str_replace( $dir, '', $this->file );
	}

	/**
	 * Locate correct class, function, line from trace
	 *
	 * @param array $trace Trace array.
	 * @return integer|null Starting postion if found, null if not.
	 *
	 * @since 1.0.0
	 */
	private function get_start_pos( array $trace ): ?int {
		try {
			$base = 0;

			foreach ( $trace as $key => $value ) {
				if ( isset( $value['function'] ) && ( 'log' === $value['function'] ) ) {
					$base = $key + 1;
					break;
				}
			}

			return $base;
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			return null;
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Get Class, Function, Line from backtrace.
	 *
	 * @param array $trace Debug backtrace.
	 * @param int   $base Starting position / base.
	 * @return void
	 *
	 * @since 1.0.0
	 */
	private function get_trace_info( array $trace, int $base ): void {
		$next = $base + 1;

		$info = array(
			'class'    => $next,
			'function' => $next,
			'line'     => $base,
			'file'     => $base,
		);

		foreach ( $info as $name => $pos ) {
			if ( isset( $trace[ $pos ][ $name ] ) ) {
				$this->$name = $trace[ $pos ][ $name ];
			}
		}

		$this->file .= "\n";
	}

	/**
	 * Convert Object, Array ect to string format.
	 *
	 * @param mixed $context Log context.
	 * @return string Converted context.
	 */
	private function fix_context( mixed $context ): string {
		$result = '';
		if ( is_wp_error( $context ) ) {

			/**
			 * Type hint.
			 *
			 * @var \WP_Error $context
			 */
			$result .= 'WP ERROR : ' . $context->get_error_message() . "\n";
		} elseif ( is_array( $context ) || is_object( $context ) ) {
			$result = print_r( $context, true );
		} elseif ( is_string( $context ) ) {
			$result .= $context;
		}

		$result .= "\n";

		return $result;
	}
}
