<?php

namespace DRPPSM\Tests;

use DRPPSM\App;
use DRPPSM\Logging\LogFormatter;
use DRPPSM\Logging\Logger;
use DRPPSM\Requirements;

class LoggerTest extends BaseTest {

	public function testLogger() {
		$result = Logger::Info( array( 'TEST' => 'INFO' ) );
		$this->assertIsBool( $result );
		$this->assertTrue( $result );

		$obj    = new \WP_Error( 'BAD', 'MESSAGE' );
		$result = Logger::error( $obj );
		$this->assertTrue( $result );

		$result = Logger::debug( 'DEBUG TEST' );
		$this->assertTrue( $result );

		$result = new LogFormatter();
		$this->assertNotNull( $result );
	}
}
