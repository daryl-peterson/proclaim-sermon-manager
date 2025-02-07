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
use DRPPSM\Logger;
use DRPPSM\Notice;

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

	public Notice $notice;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->notice = Notice::get_instance();
	}

	/**
	 * This method is called after each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function teardown(): void {
		$this->notice->delete();
	}

	/**
	 * Test check method.
	 *
	 * @since 1.0.0
	 */
	public function test_check() {

		$pe     = new PluginException( 'Test Fatal Error' );
		$result = FatalError::set( $pe );
		$this->assertTrue( $result );

		$result = FatalError::check();
		$this->assertTrue( $result );

		$this->set_admin();
		$pe     = new PluginException( 'Test Fatal Error' );
		$result = FatalError::set( $pe );

		$result = FatalError::check();
		$this->assertTrue( $result );
	}

	/**
	 * Test exist method.
	 *
	 * @since 1.0.0
	 */
	public function test_exist() {
		$pe     = new PluginException( 'Test Fatal Error' );
		$result = FatalError::set( $pe );

		$result = FatalError::exist();
		$this->assertTrue( $result );

		$this->set_admin();

		if ( ! is_admin() ) {
			Logger::debug( 'Not in admin' );
		}

		$result = FatalError::exist();
		$this->assertTrue( $result );
	}
}
