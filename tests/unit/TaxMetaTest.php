<?php
/**
 * Tax meta test class.
 *
 * @package     DRPPSM\Tests\TaxMetaTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Logger;
use DRPPSM\TaxMeta;

defined( 'ABSPATH' ) || exit;

/**
 * Tax meta test class.
 *
 * @package     DRPPSM\Tests\TaxMetaTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxMetaTest extends BaseTest {

	/**
	 * Tax meta object.
	 *
	 * @var ?TaxMeta
	 */
	private ?TaxMeta $obj;

	/**
	 * Taxonomy.
	 *
	 * @var string
	 */
	private string $tax;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = TaxMeta::exec();
		$this->tax = DRPPSM_TAX_SERIES;
	}

	/**
	 * Test register method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_register() {
		$this->assertInstanceOf( TaxMeta::class, $this->obj );

		$result = $this->obj->register();
		$this->assertIsBool( $result );

		$result = $this->obj->register();
		$this->assertFalse( $result );
	}

	/**
	 * Test get taxonomy meta.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_taxonomy_meta() {
		$terms = get_terms(
			array(
				'taxonomy'   => DRPPSM_TAX_SERIES,
				'hide_empty' => true,
				'number'     => 1,
			)
		);

		if ( is_wp_error( $terms ) ) {
			$this->markTestSkipped( 'No series found' );
		}

		if ( is_array( $terms ) ) {
			$this->assertIsArray( $terms );
			$term = array_shift( $terms );
		}
		Logger::debug( $term );

		$result = $this->obj->get_taxonomy_meta( $term->taxonomy, $term->term_id );
		if ( isset( $result ) ) {
			$this->assertIsObject( $result );
		} else {
			$this->assertNull( $result );
		}

		$result = $this->obj->get_taxonomy_meta( $term->taxonomy, 0 );
		$this->assertNull( $result );
	}

	/**
	 * Test created taxonomy.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_created_taxonomy() {
		$result = wp_insert_term( 'Test Series 02151971', $this->tax );
		$this->assertIsArray( $result );

		$term_id = $result['term_id'];
		Logger::debug( $result );
		$result = wp_delete_term( $term_id, $this->tax );
	}

	/**
	 * Test edited taxonomy.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_edited_taxonomy() {
		$result = wp_insert_term( 'Test Series 02151971', $this->tax );
		$this->assertIsArray( $result );

		$term_id = $result['term_id'];

		$term = get_term( $term_id, $this->tax );
		$this->assertInstanceOf( \WP_Term::class, $term );

		$term->name = 'Test Series 02151971 Edited';
		$result     = wp_update_term( $term_id, $this->tax, array( 'name' => $term->name ) );
		$this->assertIsArray( $result );

		$method = $this->get_method( $this->obj, 'set_term_meta' );
		$result = $method->invoke( $this->obj, 0, array() );
		$this->assertFalse( $result );

		$terms = $this->get_series_terms();
		if ( ! $terms ) {
			$this->markTestSkipped( 'No series found' );
		}

		foreach ( $terms as $term ) {
			$meta = get_term_meta( $term->term_id, $term->taxonomy . '_image_id', true );
			if ( ! isset( $meta ) || empty( $meta ) ) {
				continue;
			}
			break;
		}

		if ( isset( $meta ) && ! empty( $meta ) ) {
			$method = $this->get_method( $this->obj, 'set_term_meta' );
			$tax    = $term->taxonomy;

			$args = array(
				'taxonomy'         => $tax,
				$tax . '_image_id' => $meta,
			);

			$result = $method->invoke(
				$this->obj,
				$term->term_id,
				$args
			);
			$this->assertTrue( $result );

			$result = $method->invoke( $this->obj, 0, $args );
			$this->assertFalse( $result );

		}

		$result = wp_delete_term( $term_id, $this->tax );
	}

	/**
	 * Test set_date_meta method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_set_date_meta() {
		$method = $this->get_method( $this->obj, 'set_date_meta' );
		$result = $method->invoke( $this->obj, $this->tax, 0, $this->tax . '_date' );
		$this->assertFalse( $result );
	}

	/**
	 * Test post edit method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_post_edit() {
		$post = $this->get_test_sermon();
		if ( ! $post ) {
			$this->markTestSkipped( 'No sermon found' );
			return;
		}
		$this->assertInstanceOf( \WP_Post::class, $post );
		$result = $this->obj->post_edit( $post->ID, $post );
		$this->assertTrue( $result );
	}

	/**
	 * Get series terms.
	 *
	 * @return array|null
	 * @since 1.0.0
	 */
	private function get_series_terms(): ?array {
		$terms = get_terms(
			array(
				'taxonomy'   => $this->tax,
				'hide_empty' => true,
				'number'     => 5,
			)
		);

		if ( is_wp_error( $terms ) ) {
			return null;
		}

		if ( is_array( $terms ) ) {
			return $terms;
		}
		return null;
	}
}
