<?php
/**
 * Filter constants.
 *
 * @package     DRPPSM\Filter
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

/**
 * Filter constants.
 *
 * @package     DRPPSM\Filter
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Filter {

	/**
	 * Timer start timer filter.
	 *
	 * @param string $file File name.
	 * @param string $name Timer name.
	 * @return string Unique timer key.
	 * @var string
	 * @since 1.0.0
	 *
	 * #### Info
	 * - $file Should use __FILE__.
	 *
	 * #### Example
	 * ```php
	 *
	 * $timer_key = apply_filter( Filter::TIMER, __FILE__, __FUNCTION__ );
	 *
	 * // Stop timer
	 * do_action( Action::TIMER, $timer_key );
	 *
	 * ```
	 */
	const TIMER = 'drppsm_timer';


	/**
	 * Filters for remove submenus of admin menu.
	 *
	 * - Fires before before sub menus are displayed.
	 *
	 * @param array $submenus Sub menus to hide.
	 * @return array
	 * @since 1.0.0
	 */
	const SETTINGS_REMOVE_SUBMENU = 'drppsm_settings_remove_submenus';
}
