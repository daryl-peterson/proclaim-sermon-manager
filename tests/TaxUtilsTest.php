<?php


namespace DRPSermonManager\Tests;

use DRPSermonManager\TaxUtils;

/**
 * Tax utilities test
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxUtilsTest extends BaseTest {
	public function test_get_taxonomies() {
		$result = TaxUtils::get_taxonomies();
		$this->assertIsArray( $result );
	}

	public function test_get_taxonomy_field() {
		$result = TaxUtils::get_taxonomy_field( 'blah', 'blah_field' );
		$this->assertNull( $result );
	}
}
