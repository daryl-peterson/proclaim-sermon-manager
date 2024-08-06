<?php
/**
 * Fatal error handling.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\OptionsInt;
use DRPSermonManager\Logging\Logger;

/**
 * Fatal error handling.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class FatalError {

	/**
	 * Check if any fatal errors occured.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function check(): void {

		/**
		 * Options interface.
		 *
		 * @var OptionsInt
		 */
		$opts  = App::init()->get( OptionsInt::class );
		$error = $opts->get( 'fatal_error' );
		Logger::debug( array( 'FATAL ERROR' => $error ) );
		if ( ! isset( $error ) ) {
			return;
		}

		$name    = NAME;
		$admin   = get_admin_url( null, 'plugins.php' );
		$message = <<<EOT
			<h2>$name</h2>
			<strong>Fatal Error - $error</strong><br>
			Back to the WordPress <a href="$admin">Plugins page</a>.
		EOT;

		$opts->delete( 'fatal_error' );
		Deactivator::run();
		wp_die( $message, NAME );
	}

	/**
	 * Set error message
	 *
	 * @param string     $Message
	 * @param \Throwable $th Throwable.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public static function set( string $message, \Throwable $th ): void {
		/**
		 * Options interface.
		 *
		 * @var OptionsInt
		 */
		$opts = App::init()->get( OptionsInt::class );
		$opts->set( 'fatal_error', $message );

		Logger::error(
			array(
				'MESSAGE' => $th->getMessage(),
				'TRACE'   => $th->getTrace(),
			)
		);
	}
}
