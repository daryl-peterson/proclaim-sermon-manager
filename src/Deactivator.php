<?php
/**
 * Deactivate plugin.
 *
 * @package     Proclain Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Logging\Logger;

/**
 * Deactivate plugin.
 *
 * @package     Proclain Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class Deactivator {

	/**
	 * Run deactivation.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function run(): void {

		// @codeCoverageIgnoreStart
		if ( ! function_exists( '\is_plugin_active' ) ) {
			$file = ABSPATH . 'wp-admin/includes/plugin.php';
			Logger::debug( "Including file: $file" );
			require_once $file;
		}
		// @codeCoverageIgnoreEnd

		if ( ( is_admin() || defined( 'PHPUNIT_TESTING' ) ) ) {
			deactivate_plugins( plugin_basename( FILE ) );

			// phpcs:disable
			if ( isset( $_GET['activate'] ) ) {
				// @codeCoverageIgnoreStart
				unset( $_GET['activate'] );
				// @codeCoverageIgnoreEnd
			}
			// phpcs:enable
		}
	}
}
