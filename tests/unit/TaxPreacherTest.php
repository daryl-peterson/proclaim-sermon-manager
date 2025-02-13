<?php
/**
 * Tax preacher test.
 *
 * @package     DRPPSM\Tests\TaxPreacherTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Data\TaxPreacher;

defined( 'ABSPATH' ) || exit;

/**
 * Tax preacher test.
 *
 * @package     DRPPSM\Tests\TaxPreacherTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxPreacherTest extends BaseTest {

	/**
	 * Test construct.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_construct() {
		$sermon = $this->get_sermon_single();
		$sermon = array_shift( $sermon );

		$obj = new TaxPreacher( $sermon );
		$this->assertInstanceOf( TaxPreacher::class, $obj );

		$result = $obj->__serialize();
		$this->assertIsArray( $result );

		$obj->__unserialize( $result );
		$this->assertInstanceOf( TaxPreacher::class, $obj );

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
