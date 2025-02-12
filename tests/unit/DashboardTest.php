<?php
/**
 * Dashboard test class
 *
 * @package     DRPPSM\Tests\DashboardTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Dashboard;
use DRPPSM\Logger;
use WP_Http;

use function DRPPSM\include_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Dashboard test class
 *
 * @package     DRPPSM\Tests\DashboardTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class DashboardTest extends BaseTest {

	private ?Dashboard $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->obj = Dashboard::exec();
	}

	/**
	 * Test register method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_register() {
		$result = $this->obj->register();
		$this->assertIsBool( $result );

		$result = $this->obj->register();
		$this->assertFalse( $result );
	}

	/**
	 * Test add dashboard widget method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_add_dashboard_widget() {

		$this->set_admin();
		$this->obj->add_dashboard_widget();
		$this->assertTrue( true );

		global $wp_meta_boxes;
		$this->assertArrayHasKey( 'drppsm_dashboard_widget', $wp_meta_boxes['edit-post']['normal']['core'] );

		Logger::debug( array( 'META BOXES' => $wp_meta_boxes ) );
	}

	/**
	 * Test add dashboard widget method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_show_dashboard_widget() {

		$this->set_admin();
		$method = $this->get_method( $this->obj, 'show_dashboard_widget' );
		ob_start();
		$method->invoke( $this->obj );
		$result = ob_get_clean();
		$this->assertIsString( $result );
	}

	/**
	 * Test show glance method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_show_glance() {
		$this->set_admin();

		ob_start();
		$result = $this->obj->show_glance();
		ob_end_clean();
		$this->assertIsBool( $result );
	}
}
