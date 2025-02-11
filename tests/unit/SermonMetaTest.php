<?php
/**
 * Sermon meta test.
 *
 * @package     DRPPSM\Tests\SermonMetaTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\SermonMeta;
use WP;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Sermon meta test.
 *
 * @package     DRPPSM\Tests\SermonMetaTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonMetaTest extends BaseTest {

	private ?WP_Post $sermon;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->sermon = $this->get_post();
	}

	/**
	 * Test construct method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_construct() {
		if ( ! $this->sermon ) {
			$this->markTestSkipped( 'No sermon post found.' );
		}

		$obj = new SermonMeta( $this->sermon );
		$this->assertInstanceOf( SermonMeta::class, $obj );
	}

	/**
	 * Test serialize method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_serialize() {
		if ( ! $this->sermon ) {
			$this->markTestSkipped( 'No sermon post found.' );
		}

		$obj = new SermonMeta( $this->sermon );
		$this->assertInstanceOf( SermonMeta::class, $obj );

		$result = $obj->__serialize();
		$this->assertIsArray( $result );

		$obj->__unserialize( $result );
		$result = $this->get_property( $obj, 'post_id' );
		$this->assertEquals( $this->sermon->ID, $result );
	}

	/**
	 * Test has audio method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_has_audio() {
		if ( ! $this->sermon ) {
			$this->markTestSkipped( 'No sermon post found.' );
		}

		$obj = new SermonMeta( $this->sermon );
		$this->assertInstanceOf( SermonMeta::class, $obj );

		$result = $obj->has_audio();
		$this->assertIsBool( $result );

		$this->set_property( $obj, 'audio', 'https://www.youtube.com/watch?v=1234567890' );
		$result = $obj->has_audio();
		$this->assertIsBool( $result );
		$this->assertTrue( $result );
	}

	/**
	 * Test has video method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_has_video() {
		if ( ! $this->sermon ) {
			$this->markTestSkipped( 'No sermon post found.' );
		}

		$obj = new SermonMeta( $this->sermon );
		$this->assertInstanceOf( SermonMeta::class, $obj );

		$result = $obj->has_video();
		$this->assertIsBool( $result );

		$this->set_property( $obj, 'video', 'https://www.youtube.com/watch?v=1234567890' );
		$result = $obj->has_video();
		$this->assertIsBool( $result );

		$this->set_property( $obj, 'video', '' );
		$this->set_property( $obj, 'video_link', 'https://www.youtube.com/watch?v=1234567890' );
		$result = $obj->has_video();
		$this->assertIsBool( $result );
	}

	/**
	 * Test has bulletin method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_has_bulletin() {
		if ( ! $this->sermon ) {
			$this->markTestSkipped( 'No sermon post found.' );
		}

		$obj = new SermonMeta( $this->sermon );
		$this->assertInstanceOf( SermonMeta::class, $obj );

		$result = $obj->has_bulletin();
		$this->assertIsBool( $result );

		$this->set_property( $obj, 'bulletin', array( 'file1' ) );
		$result = $obj->has_bulletin();
		$this->assertIsBool( $result );
		$this->assertTrue( $result );
	}

	/**
	 * Test has notes method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_has_notes() {
		if ( ! $this->sermon ) {
			$this->markTestSkipped( 'No sermon post found.' );
		}

		$obj = new SermonMeta( $this->sermon );
		$this->assertInstanceOf( SermonMeta::class, $obj );

		$this->set_property( $obj, 'notes', array() );
		$result = $obj->has_notes();
		$this->assertIsBool( $result );
		$this->assertFalse( $result );

		$this->set_property( $obj, 'notes', array( 'Notes' ) );
		$result = $obj->has_notes();
		$this->assertIsBool( $result );
		$this->assertTrue( $result );
	}

	/**
	 * Test get video method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_video() {
		if ( ! $this->sermon ) {
			$this->markTestSkipped( 'No sermon post found.' );
		}

		$obj = new SermonMeta( $this->sermon );
		$this->assertInstanceOf( SermonMeta::class, $obj );

		$this->set_property( $obj, 'video_link', '' );
		$this->set_property( $obj, 'video', '' );
		$result = $obj->get_video();
		$this->assertNull( $result );

		$this->set_property( $obj, 'video_link', 'https://www.youtube.com/watch?v=1234567890' );
		$result = $obj->get_video();
		$this->assertIsString( $result );

		$this->set_property( $obj, 'video_link', '' );
		$this->set_property( $obj, 'video', 'video-file' );
		$result = $obj->get_video();
		$this->assertIsString( $result );
	}

	/**
	 * Test get audio method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_audio() {
		if ( ! $this->sermon ) {
			$this->markTestSkipped( 'No sermon post found.' );
		}

		$obj = new SermonMeta( $this->sermon );
		$this->assertInstanceOf( SermonMeta::class, $obj );

		$this->set_property( $obj, 'audio', null );
		$result = $obj->get_audio();
		$this->assertNull( $result );

		$this->set_property( $obj, 'audio', 'audio-file' );
		$result = $obj->get_audio();
		$this->assertIsString( $result );
	}

	/**
	 * Test date method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_date() {
		if ( ! $this->sermon ) {
			$this->markTestSkipped( 'No sermon post found.' );
		}

		$obj = new SermonMeta( $this->sermon );
		$this->assertInstanceOf( SermonMeta::class, $obj );

		$result = $obj->date();
		$this->assertIsString( $result );

		$this->set_property( $obj, 'date', null );
		$result = $obj->date();
		$this->assertIsString( $result );

		$this->set_property( $obj, 'date', null );
		$this->set_property( $obj, 'post_date', null );
		$result = $obj->date();
		$this->assertIsString( $result );
	}
}
