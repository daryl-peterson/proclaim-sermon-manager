<?php
/**
 * Timer to get execution time.
 *
 * @package     DRPPSM\Timer
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Timer to get execution time.
 *
 * @package     DRPPSM\Timer
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Timer implements Executable, Registrable {

	use SingletonTrait;

	/**
	 * Stores timer values.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private static ?array $data;

	/**
	 * Flag to indicate if the object has been initialized.
	 *
	 * @var bool
	 * @since 1.0.0
	 */
	private static ?bool $init;

	/**
	 * Flag to indicate if the object has been registered.
	 *
	 * @var bool
	 * @since 1.0.0
	 */
	private static ?bool $registered;

	/**
	 * Initialize object properties.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function __construct() {
		if ( ! isset( self::$init ) ) {
			self::$data = array();
			self::$init = true;
		}
	}

	/**
	 * Get the instance of the object and register hooks if needed.
	 *
	 * @return self
	 * @since 1.0.0
	 */
	public static function exec(): self {
		$obj = self::get_instance();
		$obj->register();
		return $obj;
	}

	/**
	 * Register the hooks.
	 *
	 * @return bool|null Return true if registered.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( ! isset( self::$registered ) ) {
			register_shutdown_function( array( $this, 'shutdown' ) );
			self::$registered = true;
			return true;
		}
		return false;
	}

	/**
	 * Start timer.
	 *
	 * @param mixed  $file File name.
	 * @param string $name Function name or identifier.
	 * @return string Key to use for ending the timer.
	 * @since 1.0.0
	 */
	public static function start( mixed $file, string $name = '' ): string {

		if ( empty( $name ) ) {
			$name = 'none';
		}

		$file = basename( $file );
		$file = str_replace( DRPPSM_PATH, '', $file );

		Logger::debug(
			array(
				'FILE'     => $file,
				'FUNCTION' => $name,
			)
		);

		$key                         = wp_sprintf( '%s', uniqid( "$file^$name^" ) );
		self::$data[ $key ]['start'] = hrtime( true );
		return $key;
	}

	/**
	 * End the timer.
	 *
	 * @param string $key The key returned from the start function.
	 * @return void
	 * @since 1.0.0
	 */
	public static function stop( string $key ): void {
		if ( ! key_exists( $key, self::$data ) ) {
			return;
		}

		if ( ! isset( self::$data[ $key ]['start'] ) ) {
			return;
		}
		$stop = hrtime( true );
		$exec = ( ( $stop - self::$data[ $key ]['start'] ) / 1e9 );
		$exec = sprintf( '%.8f', floatval( $exec ) ) . ' seconds';

		unset( self::$data[ $key ] );

		$name = explode( '^', $key );
		array_pop( $name );
		$file     = $name[0];
		$function = end( $name );

		$prefix       = substr( "$file $function" . str_repeat( ' ', 40 ), 0, 40 );
		self::$data[] = "$prefix -> execution time $exec";
	}

	/**
	 * Write to log.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function shutdown(): void {

		if ( count( self::$data ) === 0 ) {
			return;
		}
		Logger::debug( self::$data );
	}
}
