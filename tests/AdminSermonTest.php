<?php
/**
 * Admin sermon test.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Tests;

use DRPSermonManager\Admin\AdminSermon;
use DRPSermonManager\Constants\PT;

/**
 * Admin sermon test.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class AdminSermonTest extends BaseTest {

	protected AdminSermon $obj;

	public function setup(): void {
		$this->obj = AdminSermon::init();
	}

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
}
