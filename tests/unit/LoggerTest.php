<?php
/**
 * Logger test.
 *
 * @package     DRPPSM\Tests\LoggerTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Logger;
use DRPPSM\Logging\LogFile;

/**
 * Logger test.
 *
 * @package     DRPPSM\Tests\LoggerTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class LoggerTest extends BaseTest {

	public function test_logger() {
		$result = Logger::Info( array( 'TEST' => 'INFO' ) );
		$this->assertIsBool( $result );
		$this->assertTrue( $result );

		$obj    = new \WP_Error( 'BAD', 'MESSAGE' );
		$result = Logger::error( $obj );
		$this->assertTrue( $result );

		$result = Logger::debug( 'DEBUG TEST' );
		$this->assertTrue( $result );
	}

	/**
	 * Test the constructor.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_construct() {
		$method = $this->get_method( Logger::get_instance(), '__construct' );
		$result = $method->invoke( Logger::get_instance() );
		$this->assertNull( $result );
	}

	/**
	 * Test the set_writter method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_set_writter() {
		$writter = new LogFile();
		Logger::set_writter( $writter );
		$this->assertInstanceOf( LogFile::class, Logger::get_instance()->writter );
	}
}
