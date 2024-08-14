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

	public const TEXT_DOMAIN_LOADED = 'drppsm_textdomain';

	/**
	 * After admin init.
	 */
	public const AFTER_ADMIN_INIT = 'drppsm_after_admin_init';

	/**
	 * Register settings form.
	 *
	 * ```php
	 * # Use
	 * add_action(REGISTER_SETTINGS_FORM,[$this,'regisiter_metaboxes',10,1]);
	 *
	 * # Called - callable $display_cb
	 * do_action(REGISTER_SETTINGS_FORM, $display_cb);
	 * ```
	 *
	 * @param callable $display_cb Display callback.
	 * @since 1.0.0
	 */
	public const REGISTER_SETTINGS_FORM = 'drpsm_register_settings_form';
}
