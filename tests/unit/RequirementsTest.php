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

	public Requirements $obj;

	public function setup(): void {
		$this->obj = Requirements::exec();
	}

	public function test_register() {
		$result = $this->obj->register();
		$this->assertFalse( $result );
	}
}
