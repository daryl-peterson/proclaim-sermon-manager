<?php

namespace DRPPSM\Tests;

use DRPPSM\Activator;
use DRPPSM\Helper;

use const DRPPSM\FILE;

/**
 * Class description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class XTest extends BaseTest {

	public function testMisc() {
		Activator::run();

		$result = Helper::is_plugin_active( plugin_basename( FILE ) );
		$this->assertTrue( $result );
	}
}
