<?php
/**
 * Template blocks test.
 *
 * @package     DRPPSM\Tests\TemplateBlocksTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Logger;
use DRPPSM\TemplateBlocks;
use DRPPSM\TemplateFiles;
use WP_Exception;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;

defined( 'ABSPATH' ) || exit;

/**
 * Template blocks test.
 *
 * @package     DRPPSM\Tests\TemplateBlocksTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TemplateBlocksTest extends BaseTest {

	/**
	 * Template blocks object.
	 *
	 * @var ?TemplateBlocks
	 * @since 1.0.0
	 */
	private ?TemplateBlocks $obj;

	/**
	 * Taxonomy name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax;

	private string $pt;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->switch_to_block_theme();
		$this->tax = DRPPSM_TAX_SERIES;
		$this->pt  = DRPPSM_PT_SERMON;
		$this->obj = TemplateBlocks::exec();
	}

	/**
	 * This method is called after each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function teardown(): void {
		$this->obj = null;
	}

	/**
	 * Test register method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_register(): void {
		$result = $this->obj->register();
		$this->assertIsBool( $result );

		$result = $this->obj->register();
		$this->assertFalse( $result );
	}


	/**
	 * Test add templates.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_add_templates() {

		$args                 = array();
		$args['slug__in']     = array( 'archive-drppsm_sermon' );
		$args['slug__not_in'] = array();

		$filters = array( 'archive', 'index', 'single', 'taxonomy' );

		foreach ( $filters as $type ) {
			$key    = "{$type}_template_hierarchy";
			$result = apply_filters( $key, array() );
			$this->assertIsArray( $result );
		}

		$this->get_sermon_single();
		$result = apply_filters( 'archive_template_hierachy', array() );
		$this->assertIsArray( $result );
		Logger::info( array( 'RESULT' => $result ) );
		/*
		$result = $this->obj->add_custom_archive_template( array() );
		Logger::info( array( 'RESULT' => $result ) );
		$this->assertIsArray( $result );


		$result = apply_filters( 'index_template_hierarchy', array() );
		Logger::info( array( 'RESULT' => $result ) );
		$this->assertIsArray( $result );
		$result = $this->obj->add_custom_archive_template( array() );
		Logger::info( array( 'RESULT' => $result ) );


		$this->get_sermon_archive();
		$result = apply_filters( 'archive_template_hierarchy', array() );
		Logger::info( array( 'RESULT' => $result ) );
		$this->assertIsArray( $result );

		$this->get_sermon_single();
		$result = apply_filters( 'get_block_templates', array() );
		Logger::info( array( 'RESULT' => $result ) );
		$this->assertIsArray( $result );

		$this->get_series();
		$result = apply_filters( 'drppsm_series_template_hierarchy', array() );
		Logger::info( array( 'RESULT' => $result ) );
		$this->assertIsArray( $result );

		$args['slug__in'] = array( 'taxonomy-drppsm_series' );
		Logger::info( $args );

		$result = \apply_filters( 'get_block_templates', $result, $args, 'wp_template' );
		*/
	}

	/**
	 * Test mananage block templates.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_manage_block_templates() {

		$args                 = array();
		$args['slug__in']     = array( 'archive-drppsm_sermon' );
		$args['slug__not_in'] = array();

		$result = $this->obj->manage_block_templates( array(), $args, 'wp_template' );
		$this->assertIsArray( $result );

		// Test for not wp_template.
		$result = $this->obj->manage_block_templates( array(), $args, 'wp_template_blah' );
		$this->assertIsArray( $result );

		// Test for not our post type.
		global $post;
		$post_org = $post;
		$page     = $this->get_page();
		$post     = $page;
		$this->assertNotNull( $page );
		$result = $this->obj->manage_block_templates( array(), $args, 'wp_template' );
		$post   = $post_org;

		// Test for sermon archive.
		$this->get_sermon_archive();
		$result = $this->obj->manage_block_templates( array( 'archive-drppms_sermon' ), $args, 'wp_template' );
		$this->assertIsArray( $result );

		// Test for sermon single.
		$this->get_sermon_single();
		$result = $this->obj->manage_block_templates( array( 'single-drppsm_sermon' ), $args, 'wp_template' );
		$this->assertIsArray( $result );

		// Test for is_tax.
		$this->get_term( DRPPSM_TAX_SERIES );
		$args['slug__in'] = array( 'taxonomy-drppsm_series' );
		$result           = $this->obj->manage_block_templates( array( 'taxonomy-drppsm_series' ), $args, 'wp_template' );
		$this->assertNotNull( $result );
	}
}
