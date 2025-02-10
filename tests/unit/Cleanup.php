<?php
/**
 * Cleanup
 *
 * @package     DRPPSM\Tests\Cleanup
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Logger;

defined( 'ABSPATH' ) || exit;

/**
 * Cleanup
 *
 * @package     DRPPSM\Tests\Cleanup
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Cleanup {
	public static function run() {
		Logger::debug( 'CLEANING UP' );

		$theme = get_option( 'drppsm_phpunit_theme', false );
		delete_option( 'drppsm_phpunit_theme' );

		if ( $theme ) {
			switch_theme( $theme );
		}
	}
}
