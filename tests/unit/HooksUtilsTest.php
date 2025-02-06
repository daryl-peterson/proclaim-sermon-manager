<?php
/**
 * Hooks utils test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\HooksUtils;

/**
 * Hooks utils test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class HooksUtilsTest extends BaseTest {

	/**
	 * HooksUtils object.
	 *
	 * @var HooksUtils
	 */
	protected HooksUtils $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = HooksUtils::init();
	}

	/**
	 * Test remove_object_action method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_remove() {
		add_action( 'blah1', array( $this, 'blah1' ) );

		$result = $this->obj->remove_object_action( 'blah1', array( self::class, 'blah1' ) );
		$this->assertTrue( $result );

		$tmp = function () {
			echo 'blah2';
		};

		$result = $this->obj->remove_closure_hook( 'blah3', 10, 1 );
		$this->assertFalse( $result );

		add_action( 'blah2', $tmp, 10, 1 );
		$result = $this->obj->remove_closure_hook( 'blah2', 10, 1 );

		add_action( 'blah2', $tmp, 10, 3 );
		$result = $this->obj->remove_closure_hook( 'blah2', 10, 1 );
		$this->assertFalse( $result );

		add_action( 'blah3', array( self::class, 'blah1' ), 10, 3 );
		$result = $this->obj->remove_closure_hook( 'blah3', 10, 1 );
		$this->assertFalse( $result );
	}

	/**
	 * Just for testing.
	 *
	 * @return void
	 */
	public function blah1() {
	}
}
