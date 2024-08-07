<?php

namespace DRPPSM\Tests;

use DRPPSM\ImageUtils;

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
class ImageUtilsTest extends BaseTest {

	protected ImageUtils $obj;

	public function setup(): void {
		$this->obj = ImageUtils::init();
	}

	public function testRegister() {
		$this->obj->register();

		$result = has_action( 'after_setup_theme' );
		$this->assertTrue( $result );
	}

	public function testMisc() {
		$result = $this->obj->addImageSizes();
		$this->assertNull( $result );

		$result = has_image_size( 'sermon_small' );
		$this->assertTrue( $result );
	}
}
