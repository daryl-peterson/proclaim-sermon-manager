<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\Interfaces\TaxonomyRegInt;
use DRPSermonManager\Taxonomy\PreacherReg;

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
class TaxonomyRegTest extends BaseTest {

	public function testGetWpErrorMessage() {
		$preacher = PreacherReg::init();
		$this->assertInstanceOf( TaxonomyRegInt::class, $preacher );

		$error  = new \WP_Error( 'This is a test WP Error' );
		$result = $preacher->get_wp_error_message( $error );
		$this->assertIsString( $result );
	}
}
