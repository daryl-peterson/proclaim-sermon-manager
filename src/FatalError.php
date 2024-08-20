<?php
/**
 * Fatal error handling.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Interfaces\OptionsInt;

/**
 * Fatal error handling.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class FatalError {

	/**
	 * Check if any fatal errors occured.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function check(): void {

		/**
		 * Options interface.
		 *
		 * @var OptionsInt
		 */
		$opts  = App::init()->get( OptionsInt::class );
		$error = $opts->get( 'fatal_error' );
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

		// @codeCoverageIgnoreStart
		if ( ! defined( 'PHPUNIT_TESTING' ) ) {
			wp_die( wp_kses( $message, allowed_html() ), wp_kses_data( NAME ) );
		}
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Set error message
	 *
	 * @param string     $message Message to display.
	 * @param \Throwable $th Throwable.
	 * @return void
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
				'MESSAGE' => wp_kses( $th->getMessage(), allowed_html() ),
				'TRACE'   => (array) $th->getTrace(),
			)
		);
	}
}
