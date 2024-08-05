<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\Constants\PT;
use DRPSermonManager\Constants\TAX;
use DRPSermonManager\TaxonomyReg;
use WP_Error;

/**
 * Class description
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxonomyRegTest extends BaseTest {

	public function test_get_wp_error() {
		$obj = new TaxonomyReg( TAX::PREACHER, PT::SERMON, 'taxonomy-preacher.php' );

		$msg    = 'Test Error';
		$error  = new WP_Error( '', $msg );
		$result = $obj->get_wp_error_message( $error );
		$this->assertIsString( $result );
	}
}
