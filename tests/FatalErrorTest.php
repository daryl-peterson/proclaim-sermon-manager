<?php
/**
 * Test fatal error functions.
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Activator;
use DRPPSM\Exceptions\PluginException;
use DRPPSM\FatalError;
use DRPPSM\Helper;

use const DRPPSM\FILE;

/**
 * Test fatal error functions.
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class FatalErrorTest extends BaseTest {


	/**
	 * Test fatal error functions.
	 *
	 * @since 1.0.0
	 */
	public function test_check() {
		$pe = new PluginException( 'Test Fatal Error' );
		FatalError::set( 'Test Fatal Error', $pe );
		FatalError::check();

		$result = Helper::is_plugin_active( plugin_basename( FILE ) );
		$this->assertFalse( $result );
	}
}
