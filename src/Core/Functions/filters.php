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
define( 'DRPPSM_FLTR_TPL_PIECE', 'drppsm_tpl_piece' );

/**
 * Allows for filtering partial content.
 *
 * @param string $name File name.
 * @param array  $args Array of variables to pass to template.
 * @since 1.0.0
 */
define( 'DRPPSM_FLTR_TPL_PARTIAL', 'drppsm_tpl_partial' );

/**
 * Get settings main menu.
 * - Fires to allow another menu item can be selected.
 *
 * @param string $settings_menu Setting menu name.
 * @return string
 * @since 1.0.0
 */
define( 'DRPPSM_FLTR_SETTINGS_MM', 'drppsm_settings_main_menu' );

/**
 * Settings remove submenus.
 * - Fires before before sub menus are displayed.
 *
 * @param array $submenus Sub menus to hide.
 * @return array
 * @since 1.0.0
 */
define( 'DRPPSM_FLTR_SETTINGS_RSM', 'drppsm_settings_hidden_menu' );


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
define( 'DRPPSM_FLTR_PAGINATION_GET', 'drppsm_pagination_get' );


/**
 * Filter to flush rewrite rules.
 *
 * @return void
 * @since 1.0.0
 */
define( 'DRPPSM_FLTR_FLUSH_REWRITE', 'drppsm_flush_rewrite' );


/**
 * Filters the date a post was preached
 *
 * @since 1.0
 *
 * @param string $date                  Modified and sanitized date
 * @param string $orig_date             Original date from the database
 * @param string $format                Date format
 * @param bool   $force_unix_sanitation If the sanitation is forced
 */
define( 'DRPPSM_FLTR_SERMON_DATES', 'drppsm_fltr_sermon_dates' );



/**
 * Allows for filtering admin sermon inputs.
 *
 * @param array $output HTML Inputs for admin sermons page.
 * @since 1.0.0
 */
define( 'DRPPSM_FLTR_ADMIN_SERMON', 'drppsm_fltr_admin_sermons' );
