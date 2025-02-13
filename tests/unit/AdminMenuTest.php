<?php
/**
 * Admin menu test.
 *
 * @package     DRPPSM\Tests\AdminMenuTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Admin\AdminMenu;

defined( 'ABSPATH' ) || exit;

/**
 * Admin menu test.
 *
 * @package     DRPPSM\Tests\AdminMenuTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class AdminMenuTest extends BaseTest {

	private AdminMenu $obj;

	protected function setUp(): void {
		parent::setUp();
		$this->set_admin();
		$this->obj = AdminMenu::exec();
	}

	/**
	 * Test register.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_register(): void {
		$result = $this->obj->register();
		$this->assertIsBool( $result );

		$result = $this->obj->register();
		$this->assertFalse( $result );
	}

	/**
	 * Test fix title.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_fix_title(): void {

		do_action( 'admin_menu' );
		$this->obj->fix_title();
		$this->assertTrue( true );
	}

	/**
	 * Test load import export.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_load_import_export(): void {
		$this->obj->load_import_export();
		$this->assertTrue( true );
	}

	/**
	 * Test about.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_about(): void {
		$this->obj->load_about();
		$this->assertTrue( true );
	}
}
