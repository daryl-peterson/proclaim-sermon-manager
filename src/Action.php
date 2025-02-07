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

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

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
	 * After post setup action.
	 *
	 * @since 1.0.0
	 */
	public const AFTER_POST_SETUP = 'drppsm_after_post_setup';

	/**
	 * Text domain loaded action.
	 *
	 * @since 1.0.0
	 */
	public const TEXT_DOMAIN_LOADED = 'drppsm_textdomain';

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

	/**
	 * Action to trigger rewrite rules to flush.
	 *
	 * @since 1.0.0
	 *
	 *
	 * ```php
	 * # Example
	 * do_action( Actions::REWRITE_FLUSH );
	 * ```
	 */
	public const REWRITE_FLUSH = 'drppsm_rewrite_flush';

	/**
	 * Register settings form.
	 *
	 * @since 1.0.0
	 *
	 *
	 * ```php
	 * # Example
	 * add_action(REGISTER_SETTINGS_FORM,[$this,'regisiter_metaboxes',10,1]);
	 * ```
	 */
	public const SETTINGS_REGISTER_FORM = 'drppsm_settings_register_form';

	/**
	 * Fires when the form is ready to be displayed.
	 *
	 * @since 1.0.0
	 *
	 * ```php
	 * # Example
	 * add_action(SERMON_EDIT_FORM, array($this,'your_function') );
	 * ```
	 */
	public const SERMON_EDIT_FORM = 'drppsm_sermon_edit_form';


	/**
	 * Fires after classes have been loaded.
	 *
	 * @since 1.0.0
	 */
	public const AFTER_INIT = 'drppsm_after_init';
}
