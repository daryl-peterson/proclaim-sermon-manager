<?php
/**
 * Series shortcode test class.
 *
 * @package     DRPPSM\Tests\SCSeriesTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests\ShortCodes;

use DRPPSM\ShortCodes\Series;
use DRPPSM\Tests\BaseTest;

defined( 'ABSPATH' ) || exit;

/**
 * Series shortcode test class.
 *
 * @package     DRPPSM\Tests\SCSeriesTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SeriesTest extends BaseTest {

	private Series $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->obj = Series::exec();
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
		$this->assertIsString( $sc );

		remove_shortcode( $sc );
		$result = $this->obj->register();
		$this->assertIsBool( $result );
		$this->assertTrue( $result );

		$result = $this->obj->register();
		$this->assertFalse( $result );
	}

	/**
	 * Test fix_atts method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_fix_atts() {
		$result = $this->obj->fix_atts( array( array( 'blah' => 'blah blah' ) ) );
		$this->assertIsArray( $result );
	}

	public function test_show() {
		$result = $this->obj->show( array( 'id' => 1 ) );
		$this->assertIsString( $result );
	}
}
