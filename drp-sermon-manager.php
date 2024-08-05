<?php
/**
 * Manage Sermons with ease.
 *
 * @package     Sermon Manager
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 * ----------------------------------------------------------------------------
 *
 * @wordpress-plugin
 * Plugin Name:         DRP Sermon Manager
 * Plugin URI:
 * Description:         DRP Sermon Manager for wordpess
 * Version:             1.0.0
 * Author:              Daryl Peterson
 * Author URI:
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:         drpsermon
 * Domain Path:         /languages
 * Requires PHP:        8.1
 * Requires at least:   6.4
 * ----------------------------------------------------------------------------
 * Sermon Manager is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 */

namespace DRPSermonManager;

use DRPSermonManager\Logging\Logger;

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WPINC' ) ) {
	exit;
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Include CMB2.
 */
require_once __DIR__ . '/vendor/cmb2/cmb2/init.php';

const NAME       = 'Sermon Manager';
const FILE       = __FILE__;
const KEY_PREFIX = 'drpsermon';
const DOMAIN     = 'drpsermon';
const NS         = __NAMESPACE__;
const LOG_FILE   = 'drp-sermon-manager.log';

const PLUGIN_MIN_PHP = '8.1.0';
const PLUGIN_MIN_WP  = '6.4.0';

try {
	App::init()->plugin()->register();
} catch ( \Throwable $th ) {
	$trace = $th->getTraceAsString();
	Logger::debug(
		array(
			'MESSAGE' => $th->getMessage(),
			'TRACE'   => $th->getTrace(),
		)
	);
}
