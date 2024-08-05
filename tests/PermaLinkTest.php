<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\App;
use DRPSermonManager\Interfaces\PermaLinkInt;
use DRPSermonManager\Logging\Logger;
use DRPSermonManager\PermaLinks;

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

	public function setup(): void {
		$this->obj = App::init()->get( PermaLinkInt::class );
	}

	public function testPermaLinkStructure() {
		$result = $this->obj->get();
		$this->assertIsArray( $result );
	}
}
