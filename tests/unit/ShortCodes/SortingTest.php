<?php
/**
 * Shortcode sorting test class.
 *
 * @package     DRPPSM\Tests\SCSortingTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests\ShortCodes;

use DRPPSM\ShortCodes\Sorting;
use DRPPSM\Tests\BaseTest;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode sorting test class.
 *
 * @package     DRPPSM\Tests\SCSortingTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SortingTest extends BaseTest {

	private ?Sorting $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->obj = Sorting::exec();
	}

	/**
	 * This method is called after each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function tearDown(): void {
		parent::tearDown();
		unset( $this->obj );
	}

	/**
	 * Test register method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_register() {
		$sc = $this->get_property( $this->obj, 'sc' );
		remove_shortcode( $sc );

		$result = $this->obj->register();
		$this->assertIsBool( $result );
		$this->assertTrue( $result );

		$result = $this->obj->register();
		$this->assertIsBool( $result );
		$this->assertFalse( $result );
	}

	/**
	 * Test show_sermon_sorting method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_show_sermon_sorting() {
		$result = $this->obj->show_sermon_sorting();
		$this->assertIsString( $result );
	}
}
