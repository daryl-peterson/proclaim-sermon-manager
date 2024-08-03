<?php
/**
 * Plugin test.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace DRPSermonManager\Tests;

use DRPSermonManager\App;
use DRPSermonManager\Interfaces\PluginInt;

/**
 * Plugin test.
 */
class PluginTest extends BaseTest {

	public PluginInt $obj;

	public function setup(): void {
		$this->obj = App::getPluginInt();
	}

	public function testInit() {
		$result = $this->obj->init();
		$this->assertNull( $result );
	}

	public function testActivate() {
		$result = $this->obj->activate();
		$this->assertNull( $result );
	}

	public function testDeactivate() {
		$result = $this->obj->deactivate();
		$this->assertNull( $result );
	}

	public function testShowNotice() {
		$result = $this->obj->show_notice();
		$this->assertNull( $result );
	}

	public function testShutdown() {
		$result = $this->obj->shutdown();
		$this->assertNull( $result );
	}
}
