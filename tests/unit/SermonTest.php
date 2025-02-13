<?php
/**
 * Sermon test class.
 *
 * @package     DRPPSM\Tests\SermonTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Data\Sermon;

/**
 * Sermon test class.
 *
 * @package     DRPPSM\Tests\SermonTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonTest extends BaseTest {

	public function test_construct() {
		$sermon = $this->get_sermon_single();
		if ( ! $sermon ) {
			$this->markTestSkipped( 'No sermon found.' );
		}
		$sermon = array_shift( $sermon );

		// Test passing in a WP_Post object.
		$obj = new Sermon( $sermon );
		$this->assertInstanceOf( Sermon::class, $obj );

		// Test passing in an ID.
		$obj = new Sermon( $sermon->ID );
		$this->assertInstanceOf( Sermon::class, $obj );

		// Test passing in an invalid ID.
		$obj = new Sermon( 0 );
		$this->assertInstanceOf( Sermon::class, $obj );
	}

	public function test_serialize() {
		$sermon = $this->get_sermon_single();
		if ( ! $sermon ) {
			$this->markTestSkipped( 'No sermon found.' );
		}
		$sermon = array_shift( $sermon );

		// Test passing in a WP_Post object.
		$obj = new Sermon( $sermon );
		$this->assertInstanceOf( Sermon::class, $obj );

		$result = $obj->__serialize();
		$this->assertIsArray( $result );

		$obj->__unserialize( $result );
		$this->assertInstanceOf( Sermon::class, $obj );
	}
}
