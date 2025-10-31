<?php
/**
 * Text domain test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Action;
use DRPPSM\TextDomain;

/**
 * Text domain test.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TextDomainTest extends BaseTest {

	/**
	 * TextDomain object.
	 *
	 * @var ?TextDomain
	 */
	public ?TextDomain $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->obj = TextDomain::exec();
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
	 * Test register.
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
	 * Test load domain.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_load_domain_() {
		$result = $this->obj->load_domain();
		$this->assertTrue( $result );
	}

	/**
	 * Test functions.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_functions() {

		$result = $this->obj->switch_to_site_locale();
		$this->assertTrue( $result );

		$result = $this->obj->restore_locale();
		$this->assertTrue( $result );
	}
}
