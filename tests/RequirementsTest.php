<?php

namespace DRPPSM\Tests;

use DRPPSM\App;
use DRPPSM\Exceptions\PluginException;
use DRPPSM\Interfaces\RequirementCheckInt;
use DRPPSM\Interfaces\RequirementsInt;
use DRPPSM\RequirementCheck;
use DRPPSM\Requirements;

/**
 * Class description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class RequirementsTest extends BaseTest {

	private RequirementCheck $check;
	private RequirementsInt $require;

	public function setup(): void {
		$this->check   = $this->app->get( RequirementCheckInt::class );
		$this->require = $this->app->get( RequirementsInt::class );
	}

	public function teardown(): void {
		$this->require->notice()->delete();
	}

	public function tester() {
		wp_set_current_user( 1 );

		$result = $this->require->is_compatible();
		$this->assertNull( $result );
	}

	public function testPHPVer() {
		$this->expectException( PluginException::class );
		$this->check->check_php_ver( '9.0' );
	}

	public function testWPVer() {
		$this->expectException( PluginException::class );
		$this->check->check_wp_ver( '7.0' );
	}
}
