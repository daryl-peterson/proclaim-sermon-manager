<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\App;
use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\RequirementCheck;
use DRPSermonManager\Requirements;

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

	private RequirementCheck $obj;

	public function setup(): void {
		$this->obj = App::getRequirementCheckInt();
	}

	public function teardown(): void {
		$obj = Requirements::init();
		$obj->notice()->delete();
	}

	public function tester() {
		wp_set_current_user( 1 );

		$obj    = Requirements::init();
		$result = $obj->is_compatible();
		$this->assertNull( $result );
		$obj->is_compatible();
	}

	public function testPHPVer() {
		$this->expectException( PluginException::class );
		$this->obj->check_php_ver( '9.0' );
	}

	public function testWPVer() {
		$this->expectException( PluginException::class );
		$this->obj->check_wp_ver( '7.0' );
	}
}
