<?php
/**
 * Sermon detail test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\SermonDetail;

/**
 * Sermon detail test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonDetailsTest extends BaseTest {

	private SermonDetail $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = SermonDetail::init();
	}

	public function test_get() {
		$this->assertNotNull( $this->obj );
		$this->assertInstanceOf( SermonDetail::class, $this->obj );
	}

	public function test_show() {
		$result = $this->obj->show();
		$this->assertTrue( $result );
	}
}
