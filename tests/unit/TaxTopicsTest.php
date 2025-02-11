<?php
/**
 * Tax topics test.
 *
 * @package     DRPPSM\Tests\TaxTopicsTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\TaxTopics;

defined( 'ABSPATH' ) || exit;

/**
 * Tax topics test.
 *
 * @package     DRPPSM\Tests\TaxTopicsTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxTopicsTest extends BaseTest {

	/**
	 * Test construct.
	 *
	 * @return void
	 */
	public function test_construct() {
		$sermon = $this->get_sermon_single();
		$sermon = array_shift( $sermon );

		$series = new TaxTopics( $sermon );
		$this->assertInstanceOf( TaxTopics::class, $series );
	}
}
