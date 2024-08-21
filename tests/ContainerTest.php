<?php

namespace DRPPSM\Tests;

use DRPPSM\Interfaces\PermaLinkInt;

use function DRPPSM\app;

/**
 * Class description
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class ContainerTest extends BaseTest {
	public function test_get() {
		$result = app()->get( PermaLinkInt::class );
		$this->assertNotNull( $result );
	}
}
