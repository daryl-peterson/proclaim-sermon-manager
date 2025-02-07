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

use DRPPSM\SermonComments;
use DRPPSM\Settings;

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

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = SermonComments::exec();
	}

	public function test_init() {
		$this->assertInstanceOf( SermonComments::class, $this->obj );
	}

	public function test_register() {
		$result = $this->obj->register();
		$this->assertIsBool( $result );
	}

	/**
	 * Test default comments off.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_default_comments_off() {
		$setting = Settings::get( Settings::COMMENTS );

		$comments = array(
			'post_type' => DRPPSM_PT_SERMON,
		);
		$result   = $this->obj->default_comments_off( $comments );
		$this->assertIsArray( $result );

		Settings::set( Settings::COMMENTS, true );
		$result = $this->obj->default_comments_off( $comments );
		$this->assertIsArray( $result );
		Settings::set( Settings::COMMENTS, $setting );
	}
}
