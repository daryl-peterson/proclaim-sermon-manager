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
class Activator implements Initable, Runable {

	/**
	 * Get initialize object.
	 *
	 * @return Activator
	 *
	 * @since 1.0.0
	 */
	public static function init(): Activator {
		return new self();
	}

	/**
	 * Run activation.
	 *
	 * @since 1.0.0
	 */
	public function run(): void {
		try {
			inc_admin_plugin();

			if ( ( is_admin() && current_user_can( 'activate_plugins' ) ) || defined( 'PHPUNIT_TESTING' ) ) {
				activate_plugin( plugin_basename( FILE ) );
				// phpcs:disable
				if ( isset( $_GET['activate'] ) ) {
					// @codeCoverageIgnoreStart
					unset( $_GET['activate'] );
					// @codeCoverageIgnoreEnd
				}
				// phpcs:enable
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
	}
}
