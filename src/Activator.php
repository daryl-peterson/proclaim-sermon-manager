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

use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Runable;
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
			inc_admin_plugin();

			if ( is_admin() || defined( 'PHPUNIT_TESTING' ) ) {
				activate_plugin( plugin_basename( FILE ) );

				// phpcs:disable
				if ( isset( $_GET['activate'] ) ) {
					// @codeCoverageIgnoreStart
					unset( $_GET['activate'] );
					// @codeCoverageIgnoreEnd
				}
				// phpcs:enable

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
}
