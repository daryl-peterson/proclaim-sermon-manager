<?php
/**
 * Sermon Image Attach Test
 *
 * @package     DRPPSM\Tests\SermonImageAttachTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Logger;
use DRPPSM\SermonImageAttach;
use DRPPSM\TaxUtils;

/**
 * Sermon Image Attach Test
 *
 * @package     DRPPSM\Tests\SermonImageAttachTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonImageAttachTest extends BaseTest {

	private ?SermonImageAttach $obj;

	private ?string $tax;

	private ?string $key;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->obj = SermonImageAttach::exec();
		$this->tax = DRPPSM_TAX_SERIES;
		$this->key = "{$this->tax}_image_id";
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
	public function test_register() {
		$this->set_admin( false );
		$this->assertFalse( $this->obj->register() );

		$this->set_admin( true );
		remove_action( 'save_post', array( $this->obj, 'save_post' ) );
		$this->assertTrue( $this->obj->register() );
	}

	/**
	 * Test attach_image & detach_image methods.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_attach_detach(): void {
		$term     = $this->get_series_with_images();
		$image_id = get_term_meta( $term->term_id, $this->key, true );
		$a        = $this->get_attachment( $image_id );
		if ( ! $a ) {
			$this->markTestSkipped( 'No attachment found.' );
		}

		$sermons = TaxUtils::get_sermons_by_term( $this->tax, $term->term_id, 10 );
		if ( ! $sermons ) {
			$this->markTestSkipped( 'No sermons found.' );
		}
		$s = array_shift( $sermons );

		$a_org = $a->post_parent;

		// Test detach with invalid post_parent.
		$a->post_parent = 0;
		$method         = $this->get_method( $this->obj, 'detach_image' );
		$result         = $method->invoke( $this->obj, $a, $s );
		$this->assertIsBool( $result );
		$this->assertTrue( $result );

		// Test attach with invalid sermon.
		$method = $this->get_method( $this->obj, 'detach_image' );
		$result = $method->invoke( $this->obj, $a, $a );
		$this->assertIsBool( $result );
		$this->assertFalse( $result );

		// Detach image.
		$a->post_parent = $s->ID;
		$method         = $this->get_method( $this->obj, 'detach_image' );
		$result         = $method->invoke( $this->obj, $a, $s );
		$this->assertIsBool( $result );

		// Test not attached.
		$a->post_parent = 0;
		$method         = $this->get_method( $this->obj, 'attach_image' );
		$result         = $method->invoke( $this->obj, $a, $s );
		$this->assertIsBool( $result );

		// Test attach with invalid sermon.
		$method = $this->get_method( $this->obj, 'attach_image' );
		$result = $method->invoke( $this->obj, $a, $a );
		$this->assertIsBool( $result );

		// Test already attached.
		$a->post_parent = $s->ID;
		$method         = $this->get_method( $this->obj, 'attach_image' );
		$result         = $method->invoke( $this->obj, $a, $s );
		$this->assertIsBool( $result );

		// Restore attachment.
		$a->post_parent = $a_org;
		$method         = $this->get_method( $this->obj, 'attach_image' );
		$result         = $method->invoke( $this->obj, $a, $s );
		$this->assertIsBool( $result );
	}

	/**
	 * Test save_post method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_save_post(): void {
		$term     = $this->get_series_with_images();
		$image_id = get_term_meta( $term->term_id, $this->key, true );
		$a        = $this->get_attachment( $image_id );
		if ( ! $a ) {
			$this->markTestSkipped( 'No attachment found.' );
		}

		$sermons = TaxUtils::get_sermons_by_term( $this->tax, $term->term_id, 10 );
		if ( ! $sermons ) {
			$this->markTestSkipped( 'No sermons found.' );
		}
		$s      = array_shift( $sermons );
		$result = $this->obj->save_post( $s->ID, $s, true );
		$this->assertIsArray( $result );

		// Test with invalid post type.
		$result = $this->obj->save_post( $a->ID, $a, true );
		$this->assertIsArray( $result );
	}

	/**
	 * Test attach_thumb method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_attach_thumb(): void {
		$term     = $this->get_series_with_images();
		$image_id = get_term_meta( $term->term_id, $this->key, true );
		$a        = $this->get_attachment( $image_id );
		if ( ! $a ) {
			$this->markTestSkipped( 'No attachment found.' );
		}

		$sermons = TaxUtils::get_sermons_by_term( $this->tax, $term->term_id, 10 );
		if ( ! $sermons ) {
			$this->markTestSkipped( 'No sermons found.' );
		}
		$s = array_shift( $sermons );

		// Test with invalid post type.
		$result = $this->obj->attach_thumb( $a );
		$this->assertIsBool( $result );
		$this->assertFalse( $result );

		$thumb = has_post_thumbnail( $s->ID );
		if ( ! $thumb ) {
			add_post_meta( $s->ID, '_thumbnail_id', $image_id );
		}
		$result = $this->obj->attach_thumb( $s );
		$this->assertIsBool( $result );

		// Restore thumbnail.
		if ( ! $thumb ) {
			delete_post_meta( $s->ID, '_thumbnail_id', $image_id );
		}
	}


	/**
	 * Test attach_series method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_attach_series(): void {
		$term     = $this->get_series_with_images();
		$image_id = get_term_meta( $term->term_id, $this->key, true );
		$a        = $this->get_attachment( $image_id );
		if ( ! $a ) {
			$this->markTestSkipped( 'No attachment found.' );
			return;
		}

		$sermons = TaxUtils::get_sermons_by_term( $this->tax, $term->term_id, 10 );
		if ( ! $sermons ) {
			$this->markTestSkipped( 'No sermons found.' );
			return;
		}

		$meta = get_term_meta( $term->term_id, $this->key, true );

		// Test invalid post type.
		$result = $this->obj->attach_series( $a );
		$this->assertIsBool( $result );
		$this->assertFalse( $result );

		// Test valid post type.
		$s      = array_shift( $sermons );
		$result = $this->obj->attach_series( $s );
		$this->assertIsBool( $result );

		// Test with no image id.
		$result = delete_term_meta( $term->term_id, $this->key );
		$this->assertIsBool( $result );
		$result = $this->obj->attach_series( $s );
		$this->assertIsBool( $result );
		$this->assertFalse( $result );

		// Restore image id.
		if ( $meta !== '' ) {
			add_term_meta( $term->term_id, $this->key, $meta );
		}
	}

	/**
	 * Get attachment posts.
	 *
	 * @return ?\WP_Post
	 * @since 1.0.0
	 */
	private function get_attachment( int $image_id ): ?\WP_Post {
		$args = array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'post__in'       => array( $image_id ),
			'posts_per_page' => 1,
		);

		$attachment = get_posts( $args );

		if (
			is_wp_error( $attachment ) ||
			! is_array( $attachment ) ||
			0 === count( $attachment )
		) {
			return null;
		}

		if ( is_array( $attachment ) ) {
			$attachment = array_shift( $attachment );
		}

		return $attachment;
	}
}
