<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\App;
use DRPSermonManager\SermonDetail;

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
class SermonDetailsTest extends BaseTest {

	public function testInit() {
		$obj = App::init()->get( SermonDetail::class );
		$this->assertNotNull( $obj );
	}

	public function testShow() {
		$obj = App::init()->get( SermonDetail::class )->show();
		$this->assertNull( $obj );
	}
}
