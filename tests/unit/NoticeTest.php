<?php
/**
 * Notice testing.
 *
 * @package     DRPPSM\Tests\NoticeTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

// @codeCoverageIgnoreStart
namespace DRPPSM\Tests;

// @codeCoverageIgnoreEnd

use DRPPSM\Logger;
use DRPPSM\Notice;


/**
 * Notice testing.
 *
 * @package     DRPPSM\Tests\NoticeTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class NoticeTest extends BaseTest {

	/**
	 * Test the set methods.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_set() {
		$title   = 'This is the tile';
		$message = 'This is the message';

		$obj = Notice::get_instance();
		$obj->set_success( $title, $message );
		$obj->set_warning( $title, $message );
		$obj->set_info( $title, $message );
		$obj->set_error( $title, $message );

		ob_start();
		$obj->show_notice();
		$result = ob_get_clean();
		Logger::debug( $result );
		$this->assertIsString( $result );
		$obj->delete();

		ob_start();
		$obj->show_notice();
		$result = ob_get_clean();
		$this->assertEmpty( $result );
	}

	/**
	 * Test the register method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_register() {
		$obj    = Notice::get_instance();
		$result = $obj->register();
		$this->assertIsBool( $result );

		// Test that it was previously registered.
		$result = $obj->register();
		$this->assertFalse( $result );
	}
}
