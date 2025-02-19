<?php
/**
 * Tax image attach test class.
 *
 * @package     DRPPSM\Tests\TaxImageAttachTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Logger;
use DRPPSM\TaxImageAttach;
use DRPPSM\TaxMeta;

/**
 * Tax image attach test class.
 *
 * @package     DRPPSM\Tests\TaxImageAttachTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxImageAttachTest extends BaseTest {

	/**
	 * TaxImageAttach object.
	 *
	 * @var ?TaxImageAttach
	 */
	private ?TaxImageAttach $obj;

	/**
	 * Taxonomy name.
	 *
	 * @var ?string
	 */
	private ?string $tax;

	/**
	 * Taxonomy meta key.
	 *
	 * @var ?string
	 */
	private ?string $key;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->obj = TaxImageAttach::exec();
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
	public function test_register(): void {
		$this->set_admin( false );
		$result = $this->obj->register();
		$this->assertFalse( $result );

		$this->set_admin( true );
		$meta_type = 'term';
		remove_action( "get_{$meta_type}_metadata", array( $this->obj, 'get_metadata' ) );
		$result = $this->obj->register();
		$this->assertTrue( $result );
	}

	/**
	 * Test get_taxonomy method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_taxonomy() {

		// Test with empty meta_key.
		$method = $this->get_method( $this->obj, 'get_taxonomy' );
		$result = $method->invoke( $this->obj, '' );
		$this->assertNull( $result );

		// Test with invalid meta_key.
		$result = $method->invoke( $this->obj, 'blah' );
		$this->assertNull( $result );

		// Test with valid meta_key.
		$result = $method->invoke( $this->obj, $this->key );
		$this->assertIsString( $result );
	}

	/**
	 * Test get_sermon method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_sermon() {
		$method = $this->get_method( $this->obj, 'get_sermon' );
		$result = $method->invoke( $this->obj, 0 );
		$this->assertNull( $result );
	}

	/**
	 * Test get_image_meta method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_image_meta() {
		$series = $this->get_series_with_images();
		$method = $this->get_method( $this->obj, 'get_image_meta' );
		$result = $method->invoke( $this->obj, $series->term_id, $this->key, false, 'term' );
		$this->assertNotNull( $result );
		$this->assertIsArray( $result );

		$result = $method->invoke( $this->obj, $series->term_id, '', false, 'term' );
		$this->assertNotNull( $result );
		$this->assertIsArray( $result );
	}

	/**
	 * Test delete_meta method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_delete_meta() {
		$term = $this->get_series_with_images();
		if ( ! $term ) {
			$this->markTestSkipped( 'No series found with images.' );
			return;
		}
		$image_id = get_term_meta( $term->term_id, $this->key, true );
		$result   = $this->obj->delete_meta( array(), $term->term_id, $this->key, $image_id );
		$this->assertIsBool( $result );

		$result = $this->obj->add_meta( $term->term_id, $this->key, $image_id );
		$this->assertIsBool( $result );
	}

	/**
	 * Test add_meta method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_add_meta() {

		$series = $this->get_series();

		if ( ! $series ) {
			$this->markTestSkipped( 'No series found.' );
		}

		$meta = TaxMeta::get_taxonomy_meta( $series );
		$this->assertNotNull( array( 'TAXMETA' => $meta ) );

		$result = $this->obj->add_meta( $series->term_id, $this->key, $meta->image_id );
		$this->assertIsBool( $result );
	}

	/**
	 * Test attach method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_attach() {
		$method = $this->get_method( $this->obj, 'attach' );

		// Test invalid taxonomy.
		$result = $method->invoke( $this->obj, 0, 'blah', 0 );
		$this->assertFalse( $result );

		// Test invalid image id.
		$result = $method->invoke( $this->obj, 0, $this->key, 0 );
		$this->assertFalse( $result );

		// Test with valid image id.
		$term = $this->get_series_with_images();
		if ( ! $term ) {
			$this->markTestSkipped( 'No series found with images.' );
			return;
		}

		$image_id = get_term_meta( $term->term_id, $this->key, true );
		$result   = $method->invoke( $this->obj, $term->term_id, $this->key, $image_id );
		$this->assertIsBool( $result );
	}

	/**
	 * Test detach method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_detach() {
		$method = $this->get_method( $this->obj, 'detach' );

		// Test invalid taxonomy.
		$result = $method->invoke( $this->obj, 0, 'blah', 0 );
		$this->assertFalse( $result );

		// Test invalid image id.
		$result = $method->invoke( $this->obj, 0, $this->key, 0 );
		$this->assertFalse( $result );

		// Detach image.
		$term = $this->get_series_with_images();
		if ( ! $term ) {
			$this->markTestSkipped( 'No series found with images.' );
			return;
		}
		$image_id = get_term_meta( $term->term_id, $this->key, true );
		$result   = $method->invoke( $this->obj, $term->term_id, $this->key, $image_id );
		$this->assertIsBool( $result );

		// Attach image.
		$method = $this->get_method( $this->obj, 'attach' );
		$result = $method->invoke( $this->obj, $term->term_id, $this->key, $image_id );
		$this->assertIsBool( $result );
	}

	/**
	 * Test update_meta method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_update_meta() {
		$series = $this->get_series();

		if ( ! $series ) {
			$this->markTestSkipped( 'No series found.' );
		}

		$result = $this->obj->get_metadata( null, $series->term_id, $this->key, true, 'term' );
		$this->assertNotNull( $result );
		Logger::debug( $result );

		$result = $this->obj->update_meta( 0, $series->term_id, $this->key, $result );
		$this->assertIsBool( $result );
	}
}
