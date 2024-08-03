<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\Logging\Logger;

/**
 * Class description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class FooTest extends BaseTest {

	public function tester() {
		$obj = true;
		$this->assertTrue( $obj );

		$meta = get_post_meta( 6490, 'sermon_date', true );
		$this->assertNotNull( $meta );

		Logger::error( array( 'META' => $meta ) );
	}
}
