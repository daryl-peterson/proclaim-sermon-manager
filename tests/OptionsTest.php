<?php

namespace DRPPSM\Tests;

use DRPPSM\App;
use DRPPSM\Helper;
use DRPPSM\Interfaces\OptionsInt;
use DRPPSM\Options;

/**
 * Options test.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class OptionsTest extends BaseTest {

	public Options $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = $this->app->get( OptionsInt::class );
	}

	public function testMethods() {
		$key = Helper::get_key_name( 'delete_me_now' );
		$this->obj->delete( $key );
		$result = $this->obj->set( $key, true );
		$this->assertTrue( $result );

		// Cover setting same value
		$result = $this->obj->set( $key, true );
		$this->assertTrue( $result );

		$result = $this->obj->get( $key, false );
		$this->assertTrue( $result );

		// Cover update
		$result = $this->obj->set( $key, null );
		$this->assertTrue( $result );

		$result = $this->obj->delete( $key );
		$this->assertTrue( $result );
	}
}
