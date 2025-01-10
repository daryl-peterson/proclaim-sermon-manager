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

	private static $key = 'drppsm_fatal_error';

	public static function exist(): bool {
		return (bool) \get_option( self::$key, false );
	}

	/**
	 * Check if any fatal errors occured.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function check(): void {

		$error = \get_option( self::$key, false );
		Logger::debug( $error );

		if ( ! $error ) {
			return;
		}

		$name = DRPPSM_TITLE;
		if ( is_admin() ) {
			$link = get_admin_url( null, 'plugins.php' );
			$text = "Back to the WordPress <a href=\"$link\">Plugins page</a>.";
		} else {
			$link = get_home_url();
			$text = "Back <a href=\"$link\">Home</a>.";
		}

		$message = <<<EOT
			<h2>$name</h2>
			<strong>Fatal Error - $error</strong><br>
			$text
		EOT;

		\delete_option( self::$key );
		Deactivator::run();

		// @codeCoverageIgnoreStart
		if ( ! defined( DRPPSM_TESTING ) ) {
			wp_die( wp_kses( $message, allowed_html() ), wp_kses_data( DRPPSM_TITLE ) );
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
	public static function set( \Throwable $th ): void {

		delete_option( self::$key );
		add_option( self::$key, $th->getMessage() );

		Logger::error(
			array(
				'MESSAGE' => wp_kses( $th->getMessage(), allowed_html() ),
				'TRACE'   => (array) $th->getTrace(),
			)
		);

		// wp_die( wp_kses_data( $th->getMessage() ) );
	}
}
