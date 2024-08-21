<?php
/**
 * Test query vars class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\QueryVars;

/**
 * Test query vars class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class QueryVarsTest extends BaseTest {
	private QueryVars $obj;

	public function setup(): void {
		$this->obj = QueryVars::exec();
	}

	public function test_overwrite_query_vars() {
		$result = $this->obj->overwrite_query_vars( array( 'favicon' ) );
		$this->assertIsArray( $result );

		$result = $this->obj->register();
		$this->assertFalse( $result );

		$test = $this->getTestSermon();

		$query  = array(
			'name' => $test->post_name,
		);
		$result = $this->obj->overwrite_query_vars( $query );
		$this->assertIsArray( $result );

		$result = $this->obj->overwrite_query_vars( array( 'favicon' => true ) );
		$this->assertIsArray( $result );

		$result = $this->obj->overwrite_query_vars( array( 'drppsm_series' => 'test-series' ) );
		$this->assertIsArray( $result );
	}
}
