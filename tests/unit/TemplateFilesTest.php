<?php
/**
 * Template files test.
 *
 * @package     DRPPSM\Tests\TemplateFilesTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Logger;
use DRPPSM\Template;
use DRPPSM\TemplateBlocks;
use DRPPSM\TemplateFiles;

defined( 'ABSPATH' ) || exit;

/**
 * Template files test.
 *
 * @package     DRPPSM\Tests\TemplateFilesTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TemplateFilesTest extends BaseTest {

	/**
	 * Template files object.
	 *
	 * @var ?TemplateFiles
	 * @since 1.0.0
	 */
	private ?TemplateFiles $obj;

	/**
	 * Taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->switch_to_file_theme();
		$this->obj = TemplateFiles::exec();
		$this->tax = DRPPSM_TAX_SERIES;
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

		$obj    = TemplateBlocks::exec();
		$result = $obj->register();
		$this->assertIsBool( $result );
	}

	/**
	 * Test template include method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_template_include(): void {

		$result = $this->obj->template_include( Template::SERMON_ARCHIVE );
		$this->assertIsString( $result );

		$sermon = $this->get_test_sermon();
		if ( ! $sermon ) {
			$this->assertNull( $sermon );
		}

		// Try and test for is_post_type_archive.
		$this->get_sermon_archive();
		$result = $this->obj->template_include( Template::SERMON_ARCHIVE );
		$this->assertIsString( $result );

		// Try and test for is_singular.
		$this->get_sermon_single();
		$result = $this->obj->template_include( Template::SERMON_SINGLE );
		$this->assertIsString( $result );

		// Try and test for is_tax.
		$this->get_series();
		$result = $this->obj->template_include( Template::TAX_SERIES );
		$this->assertIsString( $result );
	}

	/**
	 * Test get partial method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_partial(): void {
		ob_start();
		$this->obj->get_partial( Template::WRAPPER_START );
		$output = ob_get_clean();

		$this->assertIsString( $output );

		$len = strlen( $output );
		$this->assertGreaterThan( 0, $len );
	}

	/**
	 * Test get archive template method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_archive_template(): void {
		$page = $this->get_page();
		$this->assertNotNull( $page );

		$method = $this->get_method( $this->obj, 'get_archive_template' );
		$result = $method->invoke( $this->obj );
		$this->assertNull( $result );
	}

	/**
	 * Test get single template method.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_single_template(): void {
		$page = $this->get_page();
		$this->assertNotNull( $page );

		$method = $this->get_method( $this->obj, 'get_single_template' );
		$result = $method->invoke( $this->obj );
		$this->assertNull( $result );
	}
}
