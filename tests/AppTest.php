<?php
/**
 * App test.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\App;
use DRPPSM\BibleLoader;
use DRPPSM\Exceptions\NotfoundException;
use DRPPSM\Interfaces\NoticeInt;
use DRPPSM\Interfaces\OptionsInt;
use DRPPSM\Interfaces\PluginInt;
use stdClass;

use function DRPPSM\allowed_html;
use function DRPPSM\app;
use function DRPPSM\app_get;
use function DRPPSM\notice;
use function DRPPSM\options;

/**
 * App test.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class AppTest extends BaseTest {

	public App $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = App::init();
	}

	public function test_get_instance() {
		$this->assertNotNull( $this->obj );

		$obj = BibleLoader::exec();
		$this->assertInstanceOf( BibleLoader::class, $obj );
	}

	public function test_app() {
		$obj = app();
		$this->assertInstanceOf( App::class, $obj );
	}

	public function test_plugin() {
		$plugin = $this->app->plugin();
		$this->assertInstanceOf( PluginInt::class, $plugin );
	}

	public function test_app_get() {
		$this->expectException( NotfoundException::class );
		app_get( 'blah' );
	}

	public function test_get_options_int() {
		$result = options();
		$this->assertInstanceOf( OptionsInt::class, $result );
	}

	public function test_get_notice_int() {
		$result = notice();
		$this->assertInstanceOf( NoticeInt::class, $result );
	}

	public function test_allowed_html() {
		$result = allowed_html();
		$this->assertIsArray( $result );
	}

	public function test_has() {
		$result = $this->app->has( 'blah' );
		$this->assertFalse( $result );
	}

	public function test_set() {

		$obj = new stdClass();
		$this->app->set( 'test', $obj );

		$result = $this->app->has( 'test' );
		$this->assertTrue( $result );
	}
}
