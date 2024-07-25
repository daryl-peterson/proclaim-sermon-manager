<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../../../../wp-load.php';

// Allows for some testing even if plugin is not enabled
if (!function_exists('\is_plugin_active')) {
    $file = ABSPATH.'wp-admin/includes/plugin.php';
    require_once $file;
}

$dir = dirname(__FILE__, 2);
$info = pathinfo($dir);
$inc_file = __DIR__.'/../'.$info['basename'].'.php';
$dir = $info['dirname'].DIRECTORY_SEPARATOR.$info['basename'].DIRECTORY_SEPARATOR.$info['basename'].'.php';
$plugin = plugin_basename($dir);

if (!is_plugin_active($plugin)) {
    require_once $inc_file;
}
