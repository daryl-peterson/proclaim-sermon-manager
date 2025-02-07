<?php
/**
 * Plugin test.
 *
 * @package     DRPPSM\Tests\PluginTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Plugin;

/**
 * Plugin test.
 *
 * @package     DRPPSM\Tests\PluginTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class PluginTest extends BaseTest {

	public ?Plugin $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = Plugin::exec();
	}

	/**
	 * This method is called after each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function teardown(): void {
		$this->obj->activate();
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
		$this->assertTrue( $result );
	}

	/**
	 * Test activate method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_activate() {
		$result = $this->obj->activate();
		$this->assertTrue( $result );
	}

	/**
	 * Test deactivate method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_deactivate() {
		$result = $this->obj->deactivate();
		$this->assertTrue( $result );
	}

	/**
	 * Test shutdown method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_shut_down() {
		$result = $this->obj->shutdown();
		$this->assertTrue( $result );
	}
}
