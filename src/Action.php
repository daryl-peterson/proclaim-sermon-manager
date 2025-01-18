<?php
/**
 * Action constants.
 *
 * @package     DRPPSM\Action
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

/**
 * Action constants.
 *
 * @package     DRPPSM\Action
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Action {

	/**
	 * Timer stop action.
	 *
	 * @param string $key Timer key.
	 * @since 1.0.0
	 *
	 * #### Info
	 * - $key Should be the key returned from the filter.
	 *
	 * #### Example
	 * ```php
	 *
	 * // Start timer
	 * $timer_key = apply_filter( Filter::TIMER, __FILE__, __FUNCTION__ );
	 *
	 * // Stop timer
	 * do_action( Action::TIMER, $timer_key );
	 *
	 * ```
	 */
	const TIMER = 'drppsm_timer';
}
