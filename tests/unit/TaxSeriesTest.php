<?php
/**
 * Tax series tests.
 *
 * @package     DRPPSM\Tests\TaxSeriesTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Data\TaxSeries;

/**
 * Tax series tests.
 *
 * @package     DRPPSM\Tests\TaxSeriesTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxSeriesTest extends BaseTest {

	/**
	 * Test construct.
	 *
	 * @return void
	 */
	public function test_construct() {
		$sermon = $this->get_sermon_single();
		$sermon = array_shift( $sermon );

		$obj = new TaxSeries( $sermon );
		$this->assertInstanceOf( TaxSeries::class, $obj );

		$result = $obj->__serialize();
		$this->assertIsArray( $result );

		$obj->__unserialize( $result );
		$this->assertInstanceOf( TaxSeries::class, $obj );

		$result = $obj->__toString();
		$this->assertIsString( $result );

		$result = $obj->name();
		$this->assertIsString( $result );

		$result = $obj->links();
		$this->assertIsArray( $result );

		$result = $obj->ids();
		$this->assertIsArray( $result );

		$result = $obj->terms();
		$this->assertIsArray( $result );

		$result = $obj->has_term();
		$this->assertIsBool( $result );

		$result = $obj->count();
		$this->assertIsInt( $result );
	}
}
