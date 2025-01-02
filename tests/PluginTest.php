<?php
/**
 * Plugin test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Interfaces\PluginInt;

/**
 * Plugin test.
 */
class PluginTest extends BaseTest {

	public PluginInt $obj;

	public function setup(): void {
		$this->obj = $this->app->plugin();
	}

	public function test_register() {
		$result = $this->obj->register();
		$this->assertTrue( $result );
	}


	public function test_activate() {
		$result = $this->obj->activate();
		$this->assertTrue( $result );
	}

	public function test_deactivate() {
		$result = $this->obj->deactivate();
		$this->assertTrue( $result );
	}

	public function test_shut_down() {
		$result = $this->obj->shutdown();
		$this->assertTrue( $result );
	}
}
