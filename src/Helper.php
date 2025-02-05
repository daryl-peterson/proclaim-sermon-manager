<?php
/**
 * Helper class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Exceptions\PluginException;
use ReflectionClass;

/**
 * Helper class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Helper {

	/**
	 * Get plugin directory.
	 *
	 * @return string
	 */
	public static function get_plugin_dir(): string {
		return plugin_dir_path( FILE );
	}

	/**
	 * Get plugin slug.
	 *
	 * @return string
	 */
	public static function get_slug(): string {
		return dirname( plugin_basename( FILE ) );
	}

	/**
	 * Get plugin url.
	 *
	 * @return string
	 */
	public static function get_url(): string {
		return plugin_dir_url( FILE );
	}

	/**
	 * Get active plugins from options.
	 *
	 * @return array
	 */
	public static function get_active_plugins(): array {
		$plugins = (array) get_option( 'active_plugins' );

		return $plugins;
	}

	/**
	 * Check if a plugin is active.
	 *
	 * @param string $name The name of the plugin.
	 * @return boolean
	 */
	public static function is_plugin_active( string $name ): bool {
		// @codeCoverageIgnoreStart
		if ( ! function_exists( '\is_plugin_active' ) ) {
			$file = ABSPATH . 'wp-admin/includes/plugin.php';
			Logger::debug( "Including file: $file" );
			require_once $file;
		}
		// @codeCoverageIgnoreEnd

		return is_plugin_active( $name );
	}

	/**
	 * GMT date to Local
	 *
	 * @param string $date  Date string to convert.
	 * @return string
	 * @since 1.0.0
	 */
	public static function gmt_to_local( string $date ) {
		$mdate = gmdate( 'Y-m-d H:i:s', strtotime( $date ) );

		$tz = new \DateTimeZone( 'GMT' );
		$dt = new \DateTime( $mdate, $tz );
		$tz = new \DateTimeZone( wp_timezone_string() );
		$dt->setTimezone( $tz );
		$mdate = $dt->format( 'Y-m-d' );

		return $mdate;
	}

	/**
	 * Get config from file.
	 *
	 * @param string $file File to load configuration from.
	 * @return mixed
	 * @throws PluginException Throws exception if the configuration file is unable to load.
	 * @since 1.0.0
	 */
	public static function get_config( string $file ): mixed {

		try {
			$path = dirname( __DIR__, 1 ) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $file;
			$info = pathinfo( $path );

			if ( ! isset( $info['extension'] ) || strtolower( 'php' !== $info['extension'] ) ) {
				$path .= '.php';
			}

			if ( file_exists( $path ) ) {
				return include $path;
			}
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
					'PATH'    => $path,
				)
			);
			throw new PluginException( esc_html( $th->getMessage() ) );
			// @codeCoverageIgnoreEnd
		}

		throw new PluginException( 'Unable able to locate config file :' . esc_html( $file ) );
	}

	/**
	 * Get class short name
	 *
	 * @param object|string $obj Class object or name.
	 * @return ?string
	 * @since 1.0.0
	 */
	public static function get_short_name( object|string $obj ): ?string {
		try {
			$reflect = new ReflectionClass( $obj );
			return $reflect->getShortName();

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			return null;
			// @codeCoverageIgnoreEnd
		}
	}
}
