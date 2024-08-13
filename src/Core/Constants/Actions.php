<?php
/**
 * Actions constants.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Constants;

/**
 * Actions constants.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Actions {
	public const AFTER_POST_SETUP  = 'drppsm_after_post_setup';
	public const AFTER_PLUGIN_LOAD = 'drppsm_plugin_loaded';

	/**
	 * Register settings form.
	 *
	 * *Called -*
	 * `apply_action(Actions::REGISTER_SETTINGS_FORM,$display_cb)`
	 *
	 * *Use -*
	 * `add_action(Actions::REGISTER_SETTINGS_FORM, $callable,10,1);`
	 *
	 * @since 1.0.0
	 */
	public const REGISTER_SETTINGS_FORM = 'drpsm_register_settings_form';
}
