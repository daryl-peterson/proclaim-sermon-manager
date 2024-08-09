<?php
/**
 * Log file test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since 1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Logging\LogFile;
use DRPPSM\Logging\Logger;

/**
 * Log file test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since 1.0.0
 */
class LogFileTest extends BaseTest {

	/**
	 * LogFile object.
	 *
	 * @var LogFile
	 */
	public LogFile $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = new LogFile();
	}

	/**
	 * Test truncate method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_truncate() {
		$file = $this->obj->get_log_file( 'info' );
		Logger::info( $_SERVER );
		Logger::info( $_SERVER );

		$size_org = wp_filesize( $file );
		$this->assertIsInt( $size_org );

		$this->obj->truncate( $file );
		$size = wp_filesize( $file );
		$this->assertIsInt( $size );

		$result = $this->obj->check_file_size( $file );
		$this->assertTrue( $result );
	}
}
