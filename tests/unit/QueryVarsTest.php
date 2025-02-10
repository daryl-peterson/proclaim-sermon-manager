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
use WP;
use WP_Post;
use WP_Query;

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
		$result = $this->obj->overwrite_request_vars( array( 'favicon' ) );
		$this->assertIsArray( $result );

		$result = $this->obj->register();
		$this->assertFalse( $result );

		$test = $this->get_test_sermon();

		$query  = array(
			'name' => $test->post_name,
		);
		$result = $this->obj->overwrite_request_vars( $query );
		$this->assertIsArray( $result );

		$result = $this->obj->overwrite_request_vars( array( 'favicon' => true ) );
		$this->assertIsArray( $result );

		$result = $this->obj->overwrite_request_vars( array( 'drppsm_series' => 'test-series' ) );
		$this->assertIsArray( $result );
	}

	public function test_add_query_vars() {
		$result = $this->obj->add_query_vars( array() );
		$this->assertIsArray( $result );
	}
	/**
	 * Test post limits.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_post_limits() {
		global $post;

		$args  = $this->get_sermon_query_args();
		$query = new WP_Query( $args );
		$query->set( 'query_type', 'drppsm_post' );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$this->assertInstanceOf( WP_Post::class, $post );
			}
			// contents of the Loop go here
		}
	}
}
