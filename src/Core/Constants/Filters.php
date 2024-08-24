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

	/**
	 * Add comments.
	 */
	public const FLUSH_REWRITE_RULES = 'drppsm_flush_rewrite_rules';
	public const SERMON_FILTER       = 'drppsm_filters';
	public const SERMON_DATES_GET    = 'drppsm_dates_get';

	/**
	 * Allows for filtering partial content.
	 *
	 * @param string $name File name.
	 * @param array  $args Array of variables to pass to template.
	 * @since 1.0.0
	 */
	public const TPL_PARTIAL = 'drppsm_tpl_partial';

	/**
	 * Allows for filtering html items. Wrappers ect.
	 *
	 * @param string $name File name.
	 * @param array  $args Array of variables to pass to template.
	 * @since 1.0.0
	 */
	public const TPL_PIECE = 'drppsm_tpl_piece';

	public const TEMPLATE_SERMON_CONTENT = 'drppsm_tpl_sermon_content';

	/**
	 * Get main settings menu.
	 * - Fires to allow another menu item can be selected.
	 *
	 * @param string $settings_menu Setting menu name.
	 * @return string
	 * @since 1.0.0
	 *
	 * ```php
	 * # Example
	 * add_filter(SETTINGS_MAIN_MENU,[$this,'set_menu',10,1]);
	 *
	 * ```
	 */
	public const SETTINGS_MAIN_MENU = 'drppsm_settings_main_menu';

	/**
	 * Get hidden setting menu names.
	 * - Fires before before sub menuS are displayed.
	 *
	 * @param array $submenus Sub menus to hide.
	 * @return array
	 * @since 1.0.0
	 *
	 * ```php
	 * # Example
	 * add_filter(SETTINGS_REMOVE_SUBMENUS,[$this,'set_menu',10,1]);
	 * ```
	 */
	public const SETTINGS_REMOVE_SUBMENUS = 'drppsm_settings_hidden_menu';

	/**
	 * Get pagination links
	 *
	 * @since 1.0.0
	 *
	 * @param integer $items Total records.
	 * @param integer $limit Per page.
	 * @param integer $page Page number.
	 * @return string
	 *
	 * ```php
	 * # Example
	 * apply_filters(PAGINATION_GET,$items,$limit,$page]);
	 * ```
	 */
	public const PAGINATION_GET = 'drppsm_pagination_get';
}
