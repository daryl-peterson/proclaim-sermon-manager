<?php
/**
 * Sermon detail test.
 *
 * @package     Proclaim Sermon Manager
 * @subpackage  DRPPSM\Tests\SermonDetailTest
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
 * @subpackage  DRPPSM\Tests\SermonDetailTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonDetailTest extends BaseTest {

	private SermonDetail $obj;

	public function setup(): void {
		$this->obj = SermonDetail::exec();
	}
	public function test_register() {
		$result = $this->obj->register();
		$this->assertIsBool( $result );

		$result = $this->obj->register();
		$this->assertFalse( $result );
	}

	public function test_get() {
		$this->assertNotNull( $this->obj );
		$this->assertInstanceOf( SermonDetail::class, $this->obj );
	}

	public function test_show() {
		$result = $this->obj->show();
		$this->assertIsBool( $result );
		$this->assertTrue( $result );
	}

	public function test_save() {
		$this->obj->show();

		// Test invalid post type.
		$page = $this->get_page();
		$cmb  = $this->get_property( $this->obj, 'cmb' );

		$result = $this->obj->save( $page->ID, array(), $cmb );
		$this->assertIsBool( $result );
		$this->assertFalse( $result );

		// Test valid post type.
		$sermon = $this->get_sermon_single();
		if ( ! is_array( $sermon ) ) {
			$this->markTestSkipped( 'No sermon found.' );
		}

		global $post;
		$sermon = array_shift( $sermon );
		$post   = $sermon;
		$this->obj->show();

		$this->obj->save( $sermon->ID, array(), $cmb );
		$this->assertIsBool( $result );
	}
}
