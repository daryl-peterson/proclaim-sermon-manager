<?php
/**
 * Transient test class.
 *
 * @package     DRPPSM\Tests\TransientTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Transient;

/**
 * Transient test class.
 *
 * @package     DRPPSM\Tests\TransientTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TransientTest extends BaseTest {

	/**
	 * Transient key.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $key;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->key = 'drppsm_test';
	}

	/**
	 * This method is called after each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function tearDown(): void {
		parent::tearDown();
		Transient::delete( $this->key );
	}

	/**
	 * Test get method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get() {
		$result = Transient::get( 'favicon' );
		$this->assertFalse( $result );
	}

	/**
	 * Test set method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_set() {
		Transient::delete( $this->key );
		$result = Transient::set( $this->key, 'test', 300 );
		$this->assertTrue( $result );
	}

	/**
	 * Test delete method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_delete() {
		Transient::set( 'drppsm_test', 'test', 300 );
		$result = Transient::delete( 'drppsm_test' );
		$this->assertNotNull( $result );
		$this->assertIsBool( $result );
	}

	/**
	 * Test delete all method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_delete_all() {
		$result = Transient::delete_all();
		$this->assertIsBool( $result );
	}
}
