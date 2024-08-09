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

use DRPPSM\App;
use DRPPSM\Helper;
use DRPPSM\Interfaces\TextDomainInt;
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

	public TextDomainInt $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = App::init()->get( TextDomainInt::class );
	}

	/**
	 * Test functions.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_functions() {
		$hook = Helper::get_key_name( TextDomain::INIT_KEY );
		do_action( $hook );
		$this->assertIsString( $hook );

		$result = $this->obj->switch_to_site_locale();
		$this->assertTrue( $result );

		$result = $this->obj->restore_locale();
		$this->assertTrue( $result );
	}
}
