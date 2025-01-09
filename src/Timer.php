<?php


/**
 * Timer to get execution time.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Timer to get execution time.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Timer {

	use SingletonTrait;


	private array $data = array();

	protected function __construct() {
		$this->data = array();
	}

	/**
	 * Start timer.
	 *
	 * @param string $function Current function name.
	 * @param string $file File name.
	 * @return string Key to use for ending the timer.
	 * @since 1.0.0
	 */
	public function start( string $function = '', string $file ): string {

		if ( empty( $function ) ) {
			$function = 'none';
		}

		$file = basename( $file );

		$key                         = wp_sprintf( '%s', uniqid( "$file^$function^" ) );
		$this->data[ $key ]['start'] = hrtime( true );
		return $key;
	}

	/**
	 * End the timer.
	 *
	 * @param string $key The key returned from the start function.
	 * @return void
	 * @since 1.0.0
	 */
	public function stop( string $key ): void {
		if ( ! key_exists( $key, $this->data ) ) {
			return;
		}

		if ( ! isset( $this->data[ $key ]['start'] ) ) {
			return;
		}
		$stop = hrtime( true );
		$exec = ( ( $stop - $this->data[ $key ]['start'] ) / 1e9 );

		$exec = sprintf( '%.8f', floatval( $exec ) ) . ' seconds';
		// $exec = rtrim( sprintf( '%f', floatval( $exec ) ), '0' ) . ' seconds';

		unset( $this->data[ $key ] );

		$name = explode( '^', $key );
		array_pop( $name );
		$file     = $name[0];
		$function = end( $name );

		$prefix = substr( "$file $function" . str_repeat( ' ', 40 ), 0, 40 );

		$this->data[] = "$prefix -> execution time $exec";
	}

	public function shutdown() {
		Logger::debug( $this->data );
	}
}
