<?php
/**
 * App test.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Tests;

use DRPSermonManager\AdminPage;
use DRPSermonManager\App;
use DRPSermonManager\BibleBookLoad;
use DRPSermonManager\Exceptions\NotfoundException;
use DRPSermonManager\Interfaces\NoticeInt;
use DRPSermonManager\Interfaces\PluginInt;
use DRPSermonManager\Logging\Logger;
use stdClass;

/**
 * App test.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class AppTest extends BaseTest {

	public App $obj;

	public function setup(): void {
		$this->obj = App::init();
	}

	public function testGetInstance() {
		$this->assertNotNull( $this->obj );
		$result = $this->app->get( NoticeInt::class );
		$this->assertNotNull( $result );

		$obj = $this->app->get( BibleBookLoad::class );
		Logger::debug( $obj );
		$this->assertInstanceOf( BibleBookLoad::class, $obj );
	}

	public function test_plugin() {
		$plugin = $this->app->plugin();
		$this->assertInstanceOf( PluginInt::class, $plugin );
	}

	public function test_get_admin_page() {
		$result = $this->obj->getAdminPage();
		$this->assertInstanceOf( AdminPage::class, $result );
	}

	public function test_has() {
		$result = $this->app->has( NoticeInt::class );
		$this->assertTrue( $result );

		$result = $this->app->has( 'blah' );
		$this->assertFalse( $result );
	}

	public function test_get_exception() {
		$this->expectException( NotfoundException::class );
		$result = $this->app->get( 'blah' );
	}

	public function test_set() {

		$obj = new stdClass();
		$this->app->set( 'test', $obj );

		$result = $this->app->has( 'test' );
		$this->assertTrue( $result );
	}
}
