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
use DRPPSM\Interfaces\RequirementCheckInt;
use DRPPSM\Interfaces\RequirementsInt;
use DRPPSM\RequirementCheck;

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

	private RequirementCheck $check;
	private RequirementsInt $require;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->check   = $this->app->get( RequirementCheckInt::class );
		$this->require = $this->app->get( RequirementsInt::class );
	}

	/**
	 * This method is called after each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function teardown(): void {
		$this->require->notice()->delete();
	}

	/**
	 * Test is_compatible method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_is_compatible() {
		wp_set_current_user( 1 );

		$result = $this->require->is_compatible();
		$this->assertTrue( $result );
	}

	/**
	 * Test check_php_ver method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_check_php_ver() {
		$this->expectException( PluginException::class );
		$this->check->check_php_ver( '9.0' );
	}

	/**
	 * Test check_wp_ver method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_check_wp_ver() {
		$this->expectException( PluginException::class );
		$this->check->check_wp_ver( '7.0' );
	}
}
