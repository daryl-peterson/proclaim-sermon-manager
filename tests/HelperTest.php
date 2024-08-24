<?php
/**
 * Test helper class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Exceptions\PluginException;
use DRPPSM\Helper;

/**
 * Test helper class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class HelperTest extends BaseTest {

	public function test_misc() {
		$result = Helper::get_key_name( 'blah' );
		$this->assertIsString( $result );

		$key    = Helper::get_key_name( '_blah' );
		$result = Helper::get_key_name( DRPSM_KEY_PREFIX . '_blah' );
		$this->assertIsString( $result );
		$this->assertEquals( $key, $result );

		$result = Helper::get_plugin_dir();
		$this->assertIsString( $result );

		$result = Helper::get_url();
		$this->assertIsString( $result );

		$result = Helper::get_slug();
		$this->assertIsString( $result );

		$result = Helper::is_compatible();
		$this->assertIsBool( $result );

		$result = Helper::gmt_to_local( gmdate( DATE_ISO8601 ) );
		$this->assertNotNull( $result );
	}

	public function test_is_plugin_active() {
		$result = Helper::is_plugin_active( 'blah' );
		$this->assertFalse( $result );
	}

	public function test_get_active_plugins() {
		$result = Helper::get_active_plugins();
		$this->assertIsArray( $result );
	}

	public function test_set_transient() {
		$key = Helper::get_key_name( 'delete_me' );
		delete_transient( $key );

		$result = Helper::get_transient( 'delete_me' );
		$this->assertFalse( $result );

		$result = Helper::set_transient( 'delete_me', true, 10 );
		$this->assertTrue( $result );
	}

	public function test_get_short_name() {
		$result = Helper::get_short_name( $this );
		$this->assertIsString( $result );
	}

	public function testGetConfig() {
		$this->expectException( PluginException::class );
		Helper::get_config( 'blah-blah' );
	}
}
