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
class Filters {

	public const FLUSH_REWRITE_RULES = 'drppsm_flush_rewrite_rules';
	public const SERMON_FILTER       = 'drppsm_filters';
	public const SERMON_DATES_GET    = 'drppsm_dates_get';

	/**
	 * Get main settings menu.
	 *
	 * ```php
	 * # Used.
	 * add_filter(OPTIONS_MAIN_MENU,[$this,'set_menu',10,1]);
	 *
	 * # Called - Get the main settings menu.
	 * $menu = apply_filters(OPTIONS_MAIN_MENU,$menu);
	 * ```
	 *
	 * @param string $settings_menu Setting menu name.
	 * @return string
	 * @since 1.0.0
	 */
	public const OPTIONS_MAIN_MENU = 'drppsm_options_main_menu';

	/**
	 * Get hidden setting menu names.
	 *
	 * ```php
	 * # Use - Add your hook
	 * add_action(Filters::OPTIONS_HIDDEN_MENUS,[$this,'some_function',10,1]);
	 *
	 * # Called - Get the list of hidden settings menus.
	 * $hidden = apply_filters(OPTIONS_HIDDEN_MENUS,array $hidden);
	 * ```
	 */
	public const OPTIONS_HIDDEN_MENUS = 'drppsm_optins_hidden_menu';
}
