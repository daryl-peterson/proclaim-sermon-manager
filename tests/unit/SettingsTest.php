<?php
/**
 * Settings test class
 *
 * @package     DRPPSM\Tests\SettingsTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Logger;
use DRPPSM\Settings;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\UnknownClassOrInterfaceException;
use PrestoPlayer\Models\Setting;

defined( 'ABSPATH' ) || exit;

/**
 * Settings test class
 *
 * @package     DRPPSM\Tests\SettingsTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SettingsTest extends BaseTest {

	private Settings $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->obj = Settings::get_instance();
	}

	/**
	 * Test get_instance method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_instance(): void {
		$instance = Settings::get_instance();
		$this->assertInstanceOf( Settings::class, $instance );
	}

	/**
	 * Test __construct method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_construct(): void {
		$method = $this->get_method( $this->obj, '__construct' );
		$result = $method->invoke( $this->obj, '__construct' );
		$this->assertNull( $result );
	}

	/**
	 * Test get_option_key method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_option_key() {
		$method = $this->get_method( $this->obj, 'get_option_key' );
		$result = $method->invoke( null, 'blah' );
		$this->assertNull( $result );

		$result = $method->invoke( null, Settings::COMMENTS );
		$this->assertNotNull( $result );
	}

	/**
	 * Test set method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_set() {

		// Test with invalid key.
		$result = Settings::set( 'test', 'test' );
		$this->assertFalse( $result );
	}

	/**
	 * Test get_defaults method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_defaults() {
		$result = Settings::get_defaults( Settings::OPTION_KEY_GENERAL );
		$this->assertIsArray( $result );

		$result = Settings::get_defaults( Settings::COMMENTS );
		$this->assertNull( $result );
	}

	/**
	 * Test get_default method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_default() {
		$result = Settings::get_default( Settings::COMMENTS, null );
		$this->assertNotNull( $result );

		$result = Settings::get_default( 'test', null );
		$this->assertNull( $result );
	}

	/**
	 * Test get method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get() {
		$result = Settings::get( Settings::COMMENTS, null );
		$this->assertNotNull( $result );

		$result = Settings::get( 'test', null );
		$this->assertNull( $result );
	}
}
