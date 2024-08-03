<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Helper;

use const DRPSermonManager\KEY_PREFIX;

class HelperTest extends BaseTest {

	public function testMisc() {
		$result = Helper::get_key_name( 'blah' );
		$this->assertIsString( $result );

		$key    = Helper::get_key_name( '_blah' );
		$result = Helper::get_key_name( KEY_PREFIX . '_blah' );
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

		$result = Helper::is_plugin_active( 'blah' );
		$this->assertFalse( $result );

		$result = Helper::get_active_plugins();
		$this->assertIsArray( $result );

		$key = Helper::get_key_name( 'delete_me' );
		delete_transient( $key );

		$result = Helper::set_transient( 'delete_me', true, 10 );
		$this->assertTrue( $result );

		$result = Helper::get_transient( 'delete_me' );
		$this->assertTrue( $result );

		$result = Helper::gmt_to_local( gmdate( DATE_ISO8601 ) );
		$this->assertNotNull( $result );
	}

	public function testGetConfig() {
		$this->expectException( PluginException::class );
		Helper::get_config( 'blah-blah' );
	}
}
