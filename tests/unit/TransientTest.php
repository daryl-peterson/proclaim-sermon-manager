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

defined( 'ABSPATH' ) || exit;

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
		$result = Transient::set( 'drppsm_test', 'test' );
		$this->assertTrue( $result );
	}

	/**
	 * Test delete method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_delete() {
		$result = Transient::delete( 'drppsm_test' );
		$this->assertNotNull( $result );
		$this->assertIsInt( $result );
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
