<?php

namespace DRPPSM\Tests;

use DRPPSM\PermaLinks;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use Reflection;
use ReflectionClass;
use ReflectionException;

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

	/**
	 * Test init_permalinks method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_init_permalinks(): void {
		$this->set_property( $this->obj, 'permalinks', null );
		$method = $this->get_method( $this->obj, 'init_permalinks' );
		$method->invoke( $this->obj );
		$result = $this->get_property( $this->obj, 'permalinks' );
		$this->assertIsArray( $result );
	}

	/**
	 * Test init_common_slug method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_init_common_slug(): void {
		$this->set_property( $this->obj, 'common_slug', null );
		$method = $this->get_method( $this->obj, 'init_common_slug' );
		$method->invoke( $this->obj );
		$result = $this->get_property( $this->obj, 'common_slug' );
		$this->assertIsBool( $result );
	}

	/**
	 * Test get method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get(): void {
		$result = PermaLinks::get();
		$this->assertIsArray( $result );
	}

	/**
	 * Test add method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_add(): void {
		$result = PermaLinks::add( 'test', 'test' );
		$this->assertIsString( $result );
	}

	/**
	 * Test delete method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_delete() {
		$result = PermaLinks::delete( 'test' );
		$this->assertIsBool( $result );

		$result = PermaLinks::delete( 'blah' );
		$this->assertFalse( $result );
	}

	/**
	 * Test object type.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_object() {
		$this->assertInstanceOf( PermaLinks::class, $this->obj );
	}

	/**
	 * Test get_instance method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_instance() {
		$obj = PermaLinks::get_instance();
		$this->assertInstanceOf( PermaLinks::class, $obj );

		$method = $this->get_method( $this->obj, '__construct' );
		$result = $method->invoke( $this->obj );
		$this->assertNull( $result, 'Is null' );
	}
}
