<?php
/**
 * Filters defined.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

/**
 * Allows for filtering html items. Wrappers ect.
 *
 * @param string $name File name.
 * @param array  $args Array of variables to pass to template.
 * @since 1.0.0
 */
define( 'DRPPSM_TPL_PIECE', 'drppsm_tpl_piece' );

/**
 * Allows for filtering partial content.
 *
 * @param string $name File name.
 * @param array  $args Array of variables to pass to template.
 * @since 1.0.0
 */
define( 'DRPPSM_TPL_PARTIAL', 'drppsm_tpl_partial' );

/**
 * Get main settings menu.
 * - Fires to allow another menu item can be selected.
 *
 * @param string $settings_menu Setting menu name.
 * @return string
 * @since 1.0.0
 */
define( 'DRPPSM_SETTINGS_MAIN_MENU', 'drppsm_settings_main_menu' );

/**
 * Get hidden setting menu names.
 * - Fires before before sub menuS are displayed.
 *
 * @param array $submenus Sub menus to hide.
 * @return array
 * @since 1.0.0
 */
define( 'DRPPSM_SETTINGS_REMOVE_SUBMENUS', 'drppsm_settings_hidden_menu' );


/**
 * Get pagination links
 *
 * @since 1.0.0
 *
 * @param integer $items Total records.
 * @param integer $limit Per page.
 * @param integer $page Page number.
 * @return string
 */
define( 'DRPPSM_PAGINATION_GET', 'drppsm_pagination_get' );
