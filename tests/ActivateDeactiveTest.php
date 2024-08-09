<?php
/**
 * Deactivator test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Activator;
use DRPPSM\Deactivator;

/**
 * Deactivator test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class ActivateDeactiveTest extends BaseTest {

	public function test_run() {
		$result = Deactivator::run();
		$this->assertTrue( $result );

		$result = Activator::run();
		$this->assertTrue( $result );
	}
}
