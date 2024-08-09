<?php
/**
 * Image sizes test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\ImageSizes;

/**
 * Image sizes test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class ImageSizesTest extends BaseTest {

	protected ImageSizes $obj;

	public function setup(): void {
		$this->obj = ImageSizes::init();
	}

	public function test_register() {
		$result = $this->obj->register();
		$this->assertTrue( $result );
	}

	public function test_add_image_sizes() {
		$this->obj->add_image_sizes();
		$this->assertNull( null );
	}
}
