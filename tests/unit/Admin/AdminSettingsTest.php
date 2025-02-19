<?php
/**
 * Admin settings test class.
 *
 * @package     DRPPSM\Tests\Admin\AdminSettingsTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests\Admin;

use CMB2_Options_Hookup;
use DRPPSM\Action;
use DRPPSM\Admin\AdminSettings;
use DRPPSM\Admin\SermonSettings;
use DRPPSM\Settings;
use DRPPSM\Tests\BaseTest;

/**
 * Admin settings test class.
 *
 * @package     DRPPSM\Tests\Admin\AdminSettingsTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class AdminSettingsTest extends BaseTest {

	private ?AdminSettings $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->obj = AdminSettings::exec();
	}

	/**
	 * This method is called after each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function tearDown(): void {
		parent::tearDown();
		$this->obj = null;
	}

	/**
	 * Test register method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_register(): void {
		$this->set_admin();
		$result = $this->obj->register();
		$this->assertIsBool( $result );

		remove_action( 'cmb2_admin_init', array( $this->obj, 'register_metaboxes' ) );
		$result = $this->obj->register();
		$this->assertIsBool( $result );
		$this->assertTrue( $result );
	}

	/**
	 * Test register_metaboxes method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_register_metaboxes() {
		$this->obj->register_metaboxes();

		$result = did_action( Action::SETTINGS_REGISTER_FORM );
		$this->assertGreaterThan( 0, $result );
	}

	/**
	 * Test display_with_tabs method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_display_with_tabs() {
		$obj = new CMB2_Options_Hookup( SermonSettings::$cmb, Settings::OPTION_KEY_SERMONS );
		$this->assertInstanceOf( CMB2_Options_Hookup::class, $obj );

		$_REQUEST['page'] = Settings::OPTION_KEY_SERMONS;

		ob_start();
		$this->obj->display_with_tabs( $obj );
		$result = ob_get_clean();

		$this->assertIsString( $result );
	}

	/**
	 * Test remove_submenus method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_remove_submenus() {
		$result = $this->obj->remove_submenus( 'edit.php?post_type=drppsm_sermon' );
		$this->assertIsString( $result );
	}
}
