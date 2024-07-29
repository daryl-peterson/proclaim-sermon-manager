<?php

namespace DRPSermonManager;

/*
 * ----------------------------------------------------------------------------
 * @wordpress-plugin
 * Plugin Name:         DRP Sermon Manager
 * Plugin URI:
 * Description:         DRP Sermon Manager for wordpess
 * Version:             1.0.0
 * Author:              Daryl Peterson
 * Author URI:
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:         drp_sermon_manager
 * Domain Path:         /languages
 * Requires PHP:        8.1
 * Requires at least:   6.4
 *
 * ----------------------------------------------------------------------------
 * Sermon Manager is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 */

defined('ABSPATH') or exit;

if (!defined('WPINC')) {
    exit;
}

if (file_exists(dirname(__FILE__).'/vendor/autoload.php')) {
    require_once dirname(__FILE__).'/vendor/autoload.php';
}

const NAME = 'Sermon Manager';
const FILE = __FILE__;
const KEY_PREFIX = 'drp_sermon_manager';
const DOMAIN = 'drp_sermon_manager';
const NS = __NAMESPACE__;
const LOG_FILE = 'drp-sermon-manager.log';

const PLUGIN_MIN_PHP = '8.1.0';
const PLUGIN_MIN_WP = '6.4.0';

try {
    App::init();
    App::getPluginInt()->init();
} catch (\Throwable $th) {
    $trace = $th->getTraceAsString();
    error_log($th->getMessage());
    error_log($trace);
}
