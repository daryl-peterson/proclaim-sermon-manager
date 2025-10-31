<?php
/**
 * Tax display list test class.
 *
 * @package     DRPPSM\Tests\TaxDisplayListTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\TaxDisplayList;

/**
 * Tax display list test class.
 *
 * @package     DRPPSM\Tests\TaxDisplayListTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxDisplayListTest extends BaseTest {

	/**
	 * Test construct method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_construct(): void {
		$obj = new TaxDisplayList( array() );
		$this->assertInstanceOf( TaxDisplayList::class, $obj );

		ob_start();
		$obj = new TaxDisplayList( array( 'display' => DRPPSM_TAX_SERIES ) );
		$this->assertInstanceOf( TaxDisplayList::class, $obj );

		$obj = new TaxDisplayList( array( 'display' => DRPPSM_TAX_SERIES ) );
		$this->assertInstanceOf( TaxDisplayList::class, $obj );
		$result = ob_get_clean();
		$this->assertIsString( $result );
	}

	/**
	 * Test set data method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_set_data(): void {
		$obj = new TaxDisplayList( array() );
		$this->assertInstanceOf( TaxDisplayList::class, $obj );

		$method = $this->get_method( TaxDisplayList::class, 'set_data' );
		$method->invoke( $obj );
	}

	/**
	 * Test get count method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_count(): void {
		ob_start();
		$obj = new TaxDisplayList( array( 'display' => DRPPSM_TAX_SERIES ) );
		$this->assertInstanceOf( TaxDisplayList::class, $obj );
		$result = ob_get_clean();
		$this->assertIsString( $result );

		$this->set_property( $obj, 'taxonomy', 'drppsm_test' );
		$result = $obj->get_count();
		$this->assertIsInt( $result );

		$method = $this->get_method( $obj, 'is_args_valid' );
		$result = $method->invoke( $obj, array( 'display' => 'drppsm_test' ) );
		$this->assertFalse( $result );
	}
}
