<?php

namespace DRPSermonManager\Tests;

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
		$obj = SermonDetail::init();
		$this->assertNotNull( $obj );
	}

	public function testShow() {
		$obj = SermonDetail::init()->show();
		$this->assertNull( $obj );
	}
}
