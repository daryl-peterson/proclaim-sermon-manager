<?php
/**
 * Deactivate plugin.
 *
 * @package     DRPPSM\Deactivator
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

/**
 * Deactivate plugin on errors.
 *
 * @package     DRPPSM\Deactivator
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Deactivator {

	/**
	 * Run deactivation.
	 *
	 * @return bool True if deactivation.
	 * @since 1.0.0
	 */
	public static function run(): bool {

		$result = false;
		try {
			include_admin_plugin();

			deactivate_plugins( plugin_basename( FILE ) );

			// phpcs:disable
			if ( isset( $_GET['activate'] ) ) {
				// @codeCoverageIgnoreStart
				unset( $_GET['activate'] );
				// @codeCoverageIgnoreEnd
			}
			// phpcs:enable
			$result = true;

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
