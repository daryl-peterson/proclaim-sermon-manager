<?php
/**
 * Admin sermon test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\SermonEdit;

/**
 * Admin sermon test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class AdminSermonTest extends BaseTest {

	protected SermonEdit $obj;

	public function setup(): void {
<<<<<<< HEAD
		$this->obj = SermonEdit::init();
=======
		$this->obj = SermonEdit::exec();
>>>>>>> 822b76c (Refactoring)
	}

	public function test_object() {
		$this->assertNotNull( $this->obj );
	}

	/*
	public function testSavePost() {
		$admin = $this->getAdminUser();

		wp_set_current_user( $admin->ID );

		$sermon = $this->getTestSermon();
		$this->assertNotNull( $sermon );
		$this->assertInstanceOf( \WP_Post::class, $sermon );

		$result = $this->obj->save_post( $sermon->ID, $sermon, true );
		$this->assertNotNull( $result );

		$sermon = $this->getTestPost();
		$result = $this->obj->save_post( $sermon->ID, $sermon, true );
		$this->assertIsInt( $result );

		define( 'DOING_AUTOSAVE', true );
		$sermon = $this->getTestSermon();
		$result = $this->obj->save_post( $sermon->ID, $sermon, true );
		$this->assertIsInt( $result );
	}


	public function test_show_meta_boxes() {
		$result = $this->obj->show_meta_boxes();
		$this->assertNull( $result );
	}

	public function test_remove_meta_boxes() {
		$result = $this->obj->remove_meta_boxes();
		$this->assertNull( $result );
	}

	*/
}
