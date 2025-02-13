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
use DRPPSM\TaxUtils;

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

	public function test_add_meta() {
		$series = $this->get_series();

		if ( ! $series ) {
			$this->markTestSkipped( 'No series found.' );
		}

		$meta = TaxMeta::get_taxonomy_meta( $series );
		$this->assertNotNull( array( 'TAXMETA' => $meta ) );

		$key    = "{$series->taxonomy}_image_id";
		$result = $this->obj->add_meta( $series->term_id, $key, $meta->image_id );
		$this->assertTrue( $result );
	}

	public function test_attach() {
		$taxonomy = DRPPSM_TAX_SERIES;

		$key = "{$taxonomy}_image_id";

		$method = $this->get_method( $this->obj, 'attach' );

		// Test invalid taxonomy.
		$result = $method->invoke( $this->obj, 0, 'blah', 0 );
		$this->assertFalse( $result );

		$result = $method->invoke( $this->obj, 0, $key, 0 );
		Logger::debug( $result );
	}

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
