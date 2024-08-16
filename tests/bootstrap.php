<?php
/**
 * PHPUnit bootstrap
 *
 * @package
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

use function DRPPSM\include_admin_plugin;

/**
 * Load WordPress core.
 */
require_once __DIR__ . '/../../../../wp-load.php';
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'ABSPATH NOT DEFINED' );
}

/**
 * Load Composer autoload.
 */
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Include wp-admin/includes/plugin.php
 */
include_admin_plugin();


$dir      = dirname( __DIR__, 1 );
$info     = pathinfo( $dir );
$inc_file = __DIR__ . '/../' . $info['basename'] . '.php';
$dir      = $info['dirname'] . DIRECTORY_SEPARATOR . $info['basename'] . DIRECTORY_SEPARATOR . $info['basename'] . '.php';
$plugin   = plugin_basename( $dir );

if ( ! is_plugin_active( $plugin ) ) {
	require_once $inc_file;
}

/**
 * Include CMB2.
 */
require_once __DIR__ . '/../vendor/cmb2/cmb2/init.php';
require_once __DIR__ . '/../vendor/cmb2/cmb2/includes/helper-functions.php';
