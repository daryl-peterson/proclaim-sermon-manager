<?php
/**
 * Test fatal error functions.
 *
 * @package     DRPPSM\Tests\FatalErrorTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Exceptions\PluginException;
use DRPPSM\FatalError;
use DRPPSM\Logger;
use DRPPSM\Notice;

/**
 * Test fatal error functions.
 *
 * @package     DRPPSM\Tests\FatalErrorTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class FatalErrorTest extends BaseTest {

	public ?Notice $notice;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->notice = Notice::exec();
	}

	/**
	 * This method is called after each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function tearDown(): void {
		parent::tearDown();
		$this->notice->delete();
		$this->notice = null;
	}

	/**
	 * Test check method.
	 *
	 * - Test when user is not admin.
	 *
	 * @since 1.0.0
	 */
	public function test_check_user() {
		$this->set_admin( false );
		$pe     = new PluginException( 'Test Fatal Error' );
		$result = FatalError::set( $pe );
		$this->assertTrue( $result );

		$result = FatalError::check();
		$this->assertTrue( $result );
	}

	/**
	 * Test check method.
	 *
	 * - Test when user is admin.
	 *
	 * @since 1.0.0
	 */
	public function test_check_admin() {
		$this->set_admin();
		$pe     = new PluginException( 'Test Fatal Error' );
		$result = FatalError::set( $pe );
		$this->assertTrue( $result );

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
	}
}
