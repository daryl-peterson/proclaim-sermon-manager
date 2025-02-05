<?php
/**
 * Manage Sermons with ease.
 *
 * @package     DRPSM\Proclaim Sermon Manager
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 *
 * ----------------------------------------------------------------------------
 *
 * @wordpress-plugin
 * Plugin Name:         Proclaim Sermon Manager
 * Plugin URI:
 * Description:         Proclaim Sermon Manager for wordpess
 * Version:             1.0.0
 * Author:              Daryl Peterson
 * Author URI:
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:         drppsm
 * Domain Path:         /languages
 * Requires PHP:        8.1
 * Requires at least:   6.4
 * ----------------------------------------------------------------------------
 *
 * @todo Implement bible-api.com
 *
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WPINC' ) ) {
	exit;
}

if ( file_exists( __DIR__ . '/lib/autoload.php' ) ) {
	require_once __DIR__ . '/lib/autoload.php';
}

/**
 * Include CMB2.
 */
require_once __DIR__ . '/lib/cmb2/cmb2/init.php';

$ds = DIRECTORY_SEPARATOR;
define( 'DRPPSM_FILE', __FILE__ );
define( 'DRPPSM_PATH', dirname( DRPPSM_FILE ) . $ds );
define( 'DRPPSM_BASENAME', plugin_basename( __FILE__ ) );
define( 'DRPPSM_URL', plugin_dir_url( __FILE__ ) );
define(
	'DRPPSM_TITLE',
	__( 'Proclaim Sermon Manager', 'drppsm' )
);

define( 'DRPPSM_MIN_PHP', '8.1.0' );
define( 'DRPPSM_MIN_WP', '6.4.0' );
define( 'DRPPSM_VER', '1.0.0' );
define( 'DRPSM_KEY_PREFIX', 'drppsm' );
define( 'DRPSM_DOMAIN', 'drppsm' );
define( 'DRPPSM_PLUGIN', 'drppsm_plugin' );


const FILE     = __FILE__;
const NS       = __NAMESPACE__;
const LOG_FILE = 'drppsm';


try {
	app()->plugin();
} catch ( \Throwable $th ) {
	FatalError::set( $th );
}
