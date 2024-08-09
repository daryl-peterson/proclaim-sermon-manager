<?php
/**
 * Sermon comments test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Constants\PT;
use DRPPSM\SermonComments;

/**
 * Sermon comments test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonCommentsTest extends BaseTest {

	private SermonComments $obj;

	public function setup(): void {
		$this->obj = SermonComments::init();
	}

	public function test_init() {
		$this->assertInstanceOf( SermonComments::class, $this->obj );
	}

	public function test_register() {
		$result = $this->obj->register();
		$this->assertTrue( $result );
	}

	public function test_default_comments_off() {
		$comments = array(
			'post_type' => PT::SERMON,
		);
		$result   = $this->obj->default_comments_off( $comments );
		$this->assertIsArray( $result );
	}
}
