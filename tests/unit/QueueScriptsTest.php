<?php
/**
 * Test queuing of scripts and styles.
 *
 * @package     DRPPSM\Tests\QueueScriptsTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\QueueScripts;

defined( 'ABSPATH' ) || exit;

/**
 * Test queuing of scripts and styles.
 *
 * @package     DRPPSM\Tests\QueueScriptsTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class QueueScriptsTest extends BaseTest {


	private QueueScripts $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->obj          = QueueScripts::exec();
		$this->obj->testing = false;
	}

	/**
	 * Test register method.
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
	 * Test register scripts and styles method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_register_scripts_styles(): void {
		$result = $this->obj->register_scripts_styles();
		$this->assertNull( $result );
	}

	/**
	 * Test enqueue scripts method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_enqueue_scripts(): void {
		$result = $this->obj->enqueue_scripts();
		$this->assertNull( $result );
	}

	/**
	 * Test admin enqueue scripts method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_admin_enqueue_scripts(): void {
		$result = $this->obj->admin_enqueue_scripts();
		$this->assertNull( $result );

		$this->obj->testing = true;

		$result = $this->obj->admin_enqueue_scripts();
		$this->assertNull( $result );
	}

	/**
	 * Test admin footer method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_admin_footer(): void {
		$result = $this->obj->admin_footer();
		$this->assertNull( $result );

		$this->obj->testing = true;

		$result = $this->obj->admin_footer();
		$this->assertNull( $result );
	}
}
