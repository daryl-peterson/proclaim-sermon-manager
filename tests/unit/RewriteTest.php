<?php
/**
 * Rewrite test class.
 *
 * @package     DRPPSM\Tests\RewriteTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Rewrite;

defined( 'ABSPATH' ) || exit;

/**
 * Rewrite test class.
 *
 * @package     DRPPSM\Tests\RewriteTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class RewriteTest extends BaseTest {

	private Rewrite $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->obj = Rewrite::exec();
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
	 * Test find conflicts method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_find_conflicts() {
		$result = $this->obj->find_conflicts();
		$this->assertIsBool( $result );

		$result = $this->obj->find_conflicts();
		$this->assertIsBool( $result );
	}

	/**
	 * Test reset method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_reset() {
		$result = get_transient( Rewrite::TRANS_NAME );
		if ( ! $result ) {
			$this->assertFalse( $result );
		} else {
			$this->assertNotNull( $result );
		}

		$this->obj->reset( DRPPSM_PLUGIN, false );
		$result = get_transient( Rewrite::TRANS_NAME );
		$this->assertFalse( $result );
	}

	/**
	 * Test get slugs method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_slugs() {
		$method = $this->get_method( $this->obj, 'get_slugs' );
		$result = $method->invoke( $this->obj );
		$this->assertIsArray( $result );
	}
}
