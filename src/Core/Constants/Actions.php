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

	public const AFTER_POST_SETUP   = 'drppsm_after_post_setup';
	public const AFTER_PLUGIN_LOAD  = 'drppsm_plugin_loaded';
	public const TEXT_DOMAIN_LOADED = 'drppsm_textdomain';

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
	 * Fires after classes have been loaded.
	 *
	 * @since 1.0.0
	 */
	public const AFTER_INIT = 'drppsm_after_init';

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
}
