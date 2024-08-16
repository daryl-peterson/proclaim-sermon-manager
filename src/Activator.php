<?php
/**
 * Activate plugin.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Logging\Logger;

/**
 * Activate plugin.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Activator {


	/**
	 * Run activation.
	 *
	 * @return bool True if successfull.
	 * @since 1.0.0
	 */
	public static function run(): bool {
		$result = false;
		try {
			include_admin_plugin();

			if ( is_plugin_active( plugin_basename( FILE ) ) ) {
				return true;
			}

			if ( is_admin() || defined( 'PHPUNIT_TESTING' ) ) {
				activate_plugin( plugin_basename( FILE ) );
				self::unset();

				$result = true;
			}

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
			// @codeCoverageIgnoreEnd
		}
		return $result;
	}

	private static function unset() {
		// phpcs:disable
		if ( isset( $_GET['activate'] ) ) {
			// @codeCoverageIgnoreStart
			unset( $_GET['activate'] );
			// @codeCoverageIgnoreEnd
		}
		// phpcs:enable
	}
}
