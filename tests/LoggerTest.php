<?php

namespace DRPPSM\Tests;

use DRPPSM\App;
use DRPPSM\Logging\Logger;

/**
 * Logger test.
 *
 * @since 1.0.0
 */
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
	}
}
