<?php
/**
 * Deactivate plugin.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Logging\Logger;

/**
 * Deactivate plugin.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
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
		inc_admin_plugin();

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
