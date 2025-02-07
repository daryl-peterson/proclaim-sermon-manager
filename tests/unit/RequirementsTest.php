<?php
/**
 * Test requirements.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Exceptions\PluginException;
use DRPPSM\Notice;
use DRPPSM\Requirements;

/**
 * Test requirements.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class RequirementsTest extends BaseTest {

	public ?Requirements $obj;

	public function setup(): void {
		$this->obj = Requirements::exec();
	}

	public function teardown(): void {
		$notice = Notice::get_instance();
		$notice->delete();
		$this->obj = null;
	}

	/**
	 * Test the register method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_register() {
		$result = $this->obj->register();
		$this->assertFalse( $result );
	}

	/**
	 * Test the run method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_run() {
		$result = $this->obj->run();
		$this->assertIsBool( $result );
	}

	/**
	 * Test the php version check.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_check_php() {
		$result = $this->obj->check_php_ver();
		$this->assertIsBool( $result );

		$this->expectException( PluginException::class );
		$this->obj->check_php_ver( '99.9' );
	}

	/**
	 * Test the WordPress version check.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_check_wp_ver() {
		$result = $this->obj->check_wp_ver();
		$this->assertIsBool( $result );

		$this->expectException( PluginException::class );
		$this->obj->check_wp_ver( '99.9' );
	}
}
