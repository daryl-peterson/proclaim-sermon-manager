<?php
/**
 * Options test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Helper;
use DRPPSM\Interfaces\OptionsInt;

use function DRPPSM\options;

/**
 * Options test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class OptionsTest extends BaseTest {

	public OptionsInt $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = options();
	}

	/**
	 * Test options methods.
	 *
	 * @since 1.0.0
	 */
	public function test_methods() {
		$key = Helper::get_key_name( 'delete_me_now' );
		$this->obj->delete( $key );

		// Cover add option.
		$result = $this->obj->set( $key, true );
		$this->assertTrue( $result );

		// Cover setting same value
		$result = $this->obj->set( $key, true );
		$this->assertTrue( $result );

		// Cover get method.
		$result = $this->obj->get( $key, false );
		$this->assertTrue( $result );

		// Cover update
		$result = $this->obj->set( $key, null );
		$this->assertTrue( $result );

		// Cover delete
		$result = $this->obj->delete( $key );
		$this->assertTrue( $result );
	}
}
