<?php

namespace DRPPSM\Tests;

use DRPPSM\App;
use DRPPSM\Interfaces\PermaLinkInt;
use DRPPSM\Logging\Logger;
use DRPPSM\PermaLinks;

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
class PermaLinkTest extends BaseTest {

	public PermaLinkInt $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = App::init()->get( PermaLinkInt::class );
	}

	public function testPermaLinkStructure() {
		$result = $this->obj->get();
		$this->assertIsArray( $result );
	}
}
