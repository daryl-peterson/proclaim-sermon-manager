<?php
/**
 * Deactivate plugin.
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

use DRPSermonManager\Interfaces\DeactivatorInt;
use DRPSermonManager\Logging\Logger;

/**
 * Deactivate plugin.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class Deactivator {

	/**
	 * Initialize object
	 *
	 * @return DeactivatorInt
	 * @since 1.0.0
	 */
	public static function init(): Deactivator {
		return new self();
	}

	/**
	 * Run deactivation.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function run(): void {
		// @codeCoverageIgnoreStart
		if ( ! function_exists( '\is_plugin_active' ) ) {
			$file = ABSPATH . 'wp-admin/includes/plugin.php';
			Logger::debug( "Including file: $file" );
			require_once $file;
		}
		// @codeCoverageIgnoreEnd

		if ( ( is_admin() && current_user_can( 'activate_plugins' ) ) || defined( 'PHPUNIT_TESTING' ) ) {
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
