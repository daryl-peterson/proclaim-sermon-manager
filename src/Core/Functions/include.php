<?php
/**
 * Plugin functions.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Logging\Logger;

/**
 * Include plugin actions functions from wp-admin/includes/plugin.php.
 *
 * @return void
 * @since 1.0.0
 */
function include_admin_plugin() {
	// @codeCoverageIgnoreStart
	if ( ! function_exists( '\is_plugin_active' ) ) {
		$file = ABSPATH . 'wp-admin/includes/plugin.php';

		require_once $file;
	}
	// @codeCoverageIgnoreEnd
}

/**
 * Include metabox / template functions from wp-admin/includes/template.php.
 *
 * @return void
 * @since 1.0.0
 */
function include_admin_template() {
	// @codeCoverageIgnoreStart
	if ( ! function_exists( '\remove_meta_box' ) ) {
		$file = ABSPATH . 'wp-admin/includes/template.php';
		require_once $file;
	}
	// @codeCoverageIgnoreEnd
}
