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

	private ?TaxImageAttach $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setUp(): void {
		parent::setUp();
		$this->obj = TaxImageAttach::exec();
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
		$result = $this->obj->register();
		$this->assertFalse( $result );
	}

	/**
	 * Test get_taxonomy method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_taxonomy() {
		$tax = DRPPSM_TAX_SERIES;

		// Test with empty meta_key.
		$method = $this->get_method( $this->obj, 'get_taxonomy' );
		$result = $method->invoke( $this->obj, '' );
		$this->assertNull( $result );

		// Test with invalid meta_key.
		$result = $method->invoke( $this->obj, 'blah' );
		$this->assertNull( $result );

		// Test with valid meta_key.
		$result = $method->invoke( $this->obj, "{$tax}_image_id" );
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
		$tax = DRPPSM_TAX_SERIES;

		$series = $this->get_series_with_images();
		$method = $this->get_method( $this->obj, 'get_image_meta' );
		$result = $method->invoke( $this->obj, $series->term_id, "{$tax}_image_id", false, 'term' );
		$this->assertNotNull( $result );
		$this->assertIsArray( $result );

		$result = $method->invoke( $this->obj, $series->term_id, '', false, 'term' );
		$this->assertNotNull( $result );
		$this->assertIsArray( $result );
	}

	public function test_delete_meta() {
		$taxonomy = DRPPSM_TAX_SERIES;
		$key      = "{$taxonomy}_image_id";
		$term     = $this->get_series_with_images();
		if ( ! $term ) {
			$this->markTestSkipped( 'No series found with images.' );
			return;
		}
		$image_id = get_term_meta( $term->term_id, $key, true );
		$result   = $this->obj->delete_meta( array(), $term->term_id, $key, $image_id );
		$this->assertIsBool( $result );

		$result = $this->obj->add_meta( $term->term_id, $key, $image_id );
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

		$key    = "{$series->taxonomy}_image_id";
		$result = $this->obj->add_meta( $series->term_id, $key, $meta->image_id );
		$this->assertIsBool( $result );
	}

	/**
	 * Test attach method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_attach() {
		$taxonomy = DRPPSM_TAX_SERIES;

		$key = "{$taxonomy}_image_id";

		$method = $this->get_method( $this->obj, 'attach' );

		// Test invalid taxonomy.
		$result = $method->invoke( $this->obj, 0, 'blah', 0 );
		$this->assertFalse( $result );

		// Test invalid image id.
		$result = $method->invoke( $this->obj, 0, $key, 0 );
		$this->assertFalse( $result );

		// Test with valid image id.
		$term = $this->get_series_with_images();
		if ( ! $term ) {
			$this->markTestSkipped( 'No series found with images.' );
			return;
		}

		$image_id = get_term_meta( $term->term_id, $key, true );
		$result   = $method->invoke( $this->obj, $term->term_id, $key, $image_id );
		$this->assertIsBool( $result );
	}

	/**
	 * Test detach method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_detach() {
		$taxonomy = DRPPSM_TAX_SERIES;

		$key = "{$taxonomy}_image_id";

		$method = $this->get_method( $this->obj, 'detach' );

		// Test invalid taxonomy.
		$result = $method->invoke( $this->obj, 0, 'blah', 0 );
		$this->assertFalse( $result );

		// Test invalid image id.
		$result = $method->invoke( $this->obj, 0, $key, 0 );
		$this->assertFalse( $result );

		// Detach image.
		$term = $this->get_series_with_images();
		if ( ! $term ) {
			$this->markTestSkipped( 'No series found with images.' );
			return;
		}
		$image_id = get_term_meta( $term->term_id, $key, true );
		$result   = $method->invoke( $this->obj, $term->term_id, $key, $image_id );
		$this->assertIsBool( $result );

		// Attach image.
		$method = $this->get_method( $this->obj, 'attach' );
		$result = $method->invoke( $this->obj, $term->term_id, $key, $image_id );
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

		$key    = "{$series->taxonomy}_image_id";
		$result = $this->obj->get_metadata( null, $series->term_id, $key, true, 'term' );
		$this->assertNotNull( $result );
		Logger::debug( $result );

		$result = $this->obj->update_meta( 0, $series->term_id, $key, $result );
		$this->assertIsBool( $result );
	}
}
