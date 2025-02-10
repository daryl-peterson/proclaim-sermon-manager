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

use DRPPSM\Logger;
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

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = QueryVars::exec();
	}

	/**
	 * Test register method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_register() {
		$result = $this->obj->register();
		$this->assertFalse( $result );
	}

	/**
	 * Test overwrite request vars method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_overwrite_query_vars() {
		$result = $this->obj->overwrite_request_vars( array( 'favicon' ) );
		$this->assertIsArray( $result );

		$args = array(
			'post_type' => DRPPSM_PT_SERMON,
			'page'      => 2,
		);

		// Test post query.
		$result = $this->obj->overwrite_request_vars( $args );
		$this->assertIsArray( $result );
		$this->assertNotEquals( $args, $result );

		// Test not concerned.
		$result = $this->obj->overwrite_request_vars( array() );
		$this->assertIsArray( $result );
		$this->assertEquals( array(), $result );

		// Test tax query.
		$args   = array(
			'post_type'      => DRPPSM_PT_SERMON,
			DRPPSM_PT_SERMON => DRPPSM_TAX_SERIES,
			'page'           => 2,
		);
		$result = $this->obj->overwrite_request_vars( $args );
		$this->assertIsArray( $result );
		$this->assertNotEquals( $args, $result );

		// Test term query.
		$args   = array(
			'post_type'       => DRPPSM_PT_SERMON,
			DRPPSM_PT_SERMON  => DRPPSM_TAX_SERIES,
			DRPPSM_TAX_SERIES => 'some-series',
		);
		$result = $this->obj->overwrite_request_vars( $args );
		$this->assertIsArray( $result );
		$this->assertNotEquals( $args, $result );
	}



	/**
	 * Test add query vars method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
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

		$this->set_admin( false );

		$args = $this->get_sermon_query_args();
		unset( $args['posts_per_page'] );
		$query = new WP_Query( $args );

		$query->is_main_query       = true;
		$query->is_singular         = false;
		$query->query['query_type'] = 'drppsm_post';
		$this->set_main_query( $query );

		// Test query.
		$result = $this->obj->post_limits( 'LIMIT 10', $query );
		$this->assertIsString( $result );
		$this->assertEquals( 'LIMIT 1', $result );

		// Test not query_type key.
		unset( $query->query['query_type'] );
		$result = $this->obj->post_limits( 'LIMIT 10', $query );
		$this->assertIsString( $result );
		$this->assertEquals( 'LIMIT 10', $result );

		// Test not is_post_type_archive.
		$query->is_post_type_archive = false;

		$result = $this->obj->post_limits( 'LIMIT 10', $query );
		$this->assertIsString( $result );
		$this->assertEquals( 'LIMIT 10', $result );
	}

	/**
	 * Test is concerned method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_is_concerned() {
		$method = $this->get_method( $this->obj, 'is_concerned' );

		// Test for taxonomy.
		$result = $method->invoke( $this->obj, array( DRPPSM_TAX_SERIES => 'some-series' ) );
		$this->assertTrue( $result );

		// Test for sermons post type.
		$result = $method->invoke( $this->obj, array( DRPPSM_PT_SERMON => true ) );
		$this->assertTrue( $result );

		// Test for not sermons post type.
		$result = $method->invoke( $this->obj, array() );
		$this->assertFalse( $result );
	}

	/**
	 * Test set term query method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_set_term_query() {
		$method = $this->get_method( $this->obj, 'set_term_query' );

		// Test for not taxonomy.
		$result = $method->invoke( $this->obj, array() );
		$this->assertNull( $result );

		// Test for taxonomy.
		$result = $method->invoke(
			$this->obj,
			array(
				DRPPSM_TAX_SERIES => 'some-series',
				'page'            => 2,
			)
		);
		$this->assertNull( $result );
	}

	/**
	 * Test set post query method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_set_post_query() {
		$method = $this->get_method( $this->obj, 'set_post_query' );

		// Test for not sermons post type.
		$result = $method->invoke( $this->obj, array() );
		$this->assertFalse( $result );

		// Test for sermons post type.
		$result = $method->invoke(
			$this->obj,
			array(
				'post_type' => DRPPSM_PT_SERMON,
				'page'      => 2,
			)
		);
		$this->assertIsArray( $result );
	}

	/**
	 * Test set tax query method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_set_tax_query() {
		$method = $this->get_method( $this->obj, 'set_tax_query' );

		// Test for not sermons post type.
		$result = $method->invoke( $this->obj, array() );
		$this->assertFalse( $result );

		// Test for not taxonomy.
		$result = $method->invoke(
			$this->obj,
			array(
				'post_type'       => DRPPSM_PT_SERMON,
				DRPPSM_PT_SERMON  => DRPPSM_TAX_SERIES,
				DRPPSM_TAX_SERIES => 'some-series',
			)
		);
		$this->assertFalse( $result );

		// Test for taxonomy.
		$result = $method->invoke(
			$this->obj,
			array(
				'post_type'      => DRPPSM_PT_SERMON,
				DRPPSM_PT_SERMON => DRPPSM_TAX_SERIES,
				'page'           => 2,
			)
		);
		$this->assertTrue( $result );
	}
}
