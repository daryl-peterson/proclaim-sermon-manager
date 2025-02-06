<?php

namespace DRPPSM\Tests;

use DRPPSM\PermaLinks;
use Reflection;
use ReflectionClass;

/**
 * Class description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class PermaLinkTest extends BaseTest {

	public PermaLinks $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = PermaLinks::get_instance();
	}

	public function test_get() {
		$result = PermaLinks::get();
		$this->assertIsArray( $result );
	}

	public function test_add() {
		$result = PermaLinks::add( 'test', 'test' );
		$this->assertIsString( $result );
	}

	public function test_delete() {
		$result = PermaLinks::delete( 'test' );
		$this->assertIsBool( $result );

		$result = PermaLinks::delete( 'blah' );
		$this->assertFalse( $result );
	}

	public function test_object() {
		$this->assertInstanceOf( PermaLinks::class, $this->obj );
	}

	public function test_get_instance() {
		$obj = PermaLinks::get_instance();
		$this->assertInstanceOf( PermaLinks::class, $obj );

		$method = $this->get_method( $this->obj, '__construct' );
		$result = $method->invoke( $this->obj );
		$this->assertNull( $result, 'Is null' );
	}
}
