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
	 * Option key.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private static string $option_key = DRPPSM_PLUGIN;

	/**
	 *
	 * @var string
	 */
	private static string $option_name = 'fatal_error';

	/**
	 * Check if a fatal error exist.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public static function exist(): bool {

		$options = get_option( self::$option_key, array() );
		Logger::debug( $options );
		if ( ! is_array( $options ) || ! key_exists( self::$option_name, $options ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Check if any fatal errors occured.
	 *
	 * @return ?bool
	 * @since 1.0.0
	 */
	public static function check(): ?bool {

		$options = get_option( self::$option_key, array() );
		if ( ! is_array( $options ) || ! key_exists( self::$option_name, $options ) ) {
			return false;
		}

		$error = $options[ self::$option_name ];
		if ( ! $error ) {
			return false;
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

		unset( $options[ self::$option_name ] );
		update_option( self::$option_key, $options );

		// @codeCoverageIgnoreStart
		if ( ! defined( DRPPSM_TESTING ) ) {
			Deactivator::run();
			wp_die( wp_kses( $message, allowed_html() ), wp_kses_data( DRPPSM_TITLE ) );
		}
		// @codeCoverageIgnoreEnd

		return true;
	}

	/**
	 * Store error message in options table.
	 *
	 * @param \Throwable $th Throwable interface.
	 * @return bool
	 * @since 1.0.0
	 */
	public static function set( \Throwable $th ): bool {

		$options = get_option( self::$option_key, array() );
		if ( ! is_array( $options ) || ! key_exists( self::$option_name, $options ) ) {
			$options = array();
		}
		$options[ self::$option_name ] = $th->getMessage();
		$result                        = update_option( self::$option_key, $options );

		Logger::error(
			array(
				'MESSAGE' => wp_kses( $th->getMessage(), allowed_html() ),
				'TRACE'   => (array) $th->getTrace(),
			)
		);

		return $result;

		// phpcs:ignore
		// wp_die( wp_kses_data( $th->getMint "string"; found "\Throwable" for $messageessage() ) );
	}
}
