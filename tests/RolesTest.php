<?php
/**
 * Test role capabilities.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Constants\Caps;
use DRPPSM\Constants\PT;
use DRPPSM\Interfaces\RolesInt;
use DRPPSM\Logger;

/**
 * Test role capabilities.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class RolesTest extends BaseTest {

	public RolesInt $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 */
	public function setup(): void {
		$this->obj = $this->app->get( RolesInt::class );
	}

	/**
	 * Test role add.
	 *
	 * @return void
	 */
	public function test_add() {
		$result = $this->obj->add();
		$this->assertIsArray( $result );
		Logger::debug( $result );
	}

	/**
	 * Test role remove.
	 *
	 * @return void
	 */
	public function test_remove() {
		$result = $this->obj->remove();
		$this->assertIsArray( $result );
		$this->obj->add();
		Logger::debug( $result );
	}

	/**
	 * Test administrator.
	 *
	 * @return void
	 */
	public function test_admin() {
		$role = get_role( Caps::ROLE_ADMIN );
		$this->assertInstanceOf( \WP_Role::class, $role );

		$list = Caps::LIST;
		foreach ( $list as $cap ) {
			$has = $role->has_cap( $cap );
			Logger::debug(
				array(
					'CAP' => $cap,
					'HAS' => $has,
				)
			);
			$this->assertTrue( $has );
		}
	}

	/**
	 * Test get role caps.
	 *
	 * @return void
	 */
	public function test_get_role_caps() {

		$result = $this->obj->get_role_caps();
		$this->assertIsArray( $result );

		foreach ( $result as $role_name => $caps ) {

			$role = get_role( $role_name );
			if ( ! $role instanceof \WP_Role ) {
				continue;
			}
			foreach ( $caps as $key => $value ) {
				if ( strpos( $key, PT::SERMON ) !== false ) {
					$role->remove_cap( $key );

					$result = $role->has_cap( $key );
					$this->isFalse( $result );
				}
			}
		}
		$result = $this->obj->add();
		$this->assertIsArray( $result );
		Logger::debug( $result );

		$result = $this->obj->get_role_caps();
		$this->assertIsArray( $result );
		Logger::debug( $result );
	}

	/**
	 * Test is valid role.
	 *
	 * @return void
	 */
	public function test_is_valid_role() {
		$result = $this->obj->is_valid_role( 'blah' );
		$this->assertFalse( $result );
	}
}
