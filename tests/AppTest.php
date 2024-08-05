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
use DRPSermonManager\Interfaces\NoticeInt;
use DRPSermonManager\Logging\Logger;


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

	public function testGetPluginInt() {
		$plugin = $this->app->plugin();
		// $this->assertInstanceOf( PluginInt::class, $plugin );
		$this->assertNotNull( $plugin );
	}

	public function testGetAdminPage() {
		$result = $this->obj->getAdminPage();
		$this->assertInstanceOf( AdminPage::class, $result );
	}
}
