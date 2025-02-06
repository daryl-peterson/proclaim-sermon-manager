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

// Get path and file names.
$wp_dir      = dirname( __DIR__, 5 );
$plugin_dir  = dirname( __DIR__, 2 );
$info        = pathinfo( $plugin_dir );
$plugin_file = $plugin_dir . '/' . $info['basename'] . '.php';
$wp_load     = "{$wp_dir}/wp-load.php";
$composer    = "{$plugin_dir}/lib/autoload.php";
$cmb_inc1    = "$plugin_dir/lib/cmb2/cmb2/init.php";
$cmb_inc2    = "$plugin_dir/lib/cmb2/cmb2/includes/helper-functions.php";

if ( ! file_exists( $wp_load ) ) {
	echo "WP LOAD FILE NOT FOUND: $wp_load\n";
	exit;
}

if ( ! file_exists( $composer ) ) {
	echo "COMPOSER AUTOLOAD FILE NOT FOUND: $composer\n";
	exit;
}

if ( ! file_exists( $plugin_file ) ) {
	echo "PLUGIN FILE NOT FOUND: $plugin_file\n";
	exit;
}

if ( ! file_exists( $cmb_inc1 ) ) {
	echo "CMB2 INIT FILE NOT FOUND: $cmb_inc1\n";
	exit;
}

/**
 * Load WordPress core.
 */
require_once "{$wp_dir}/wp-load.php";

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'ABSPATH NOT DEFINED' );
}

/**
 * Load Composer autoload.
 */
require_once $composer;

/**
 * Include wp-admin/includes/plugin.php
 */
include_admin_plugin();


/**
 * Get plugin name to see if it's active.
 */
$plugin = plugin_basename( $plugin_file );


if ( ! is_plugin_active( $plugin ) ) {
	require_once $plugin_file;
}

/**
 * Include CMB2.
 */

require_once $cmb_inc1;
require_once $cmb_inc2;
