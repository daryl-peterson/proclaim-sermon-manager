<?php
/**
 * Bible loader test.
 *
 * @package     DRPPSM\Tests\BibleLoaderTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\BibleLoader;
use DRPPSM\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Bible loader test.
 *
 * @package     DRPPSM\Tests\BibleLoaderTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class BibleLoaderTest extends BaseTest {

	private ?BibleLoader $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->obj = BibleLoader::exec();
	}

	/**
	 * This method is called after each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function tearDown(): void {
		parent::tearDown();
		$this->obj = null;
	}

	/**
	 * Test register method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_register() {
		$result = $this->obj->register();
		$this->assertFalse( $result );
	}

	/**
	 * Test run method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_run() {
		Settings::set( Settings::BIBLE_BOOK_LOAD, true );
		$result = $this->obj->run();
		$this->assertIsBool( $result );
	}
}
