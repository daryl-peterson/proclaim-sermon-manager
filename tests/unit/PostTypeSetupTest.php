<?php
/**
 * Post type setup test.
 *
 * @package     DRPPSM\Tests\PostTypeSetupTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Exceptions\PluginException;
use DRPPSM\PostTypeReg;
use DRPPSM\PostTypeSetup;

/**
 * Post type setup test.
 *
 * @package     DRPPSM\Tests\PostTypeSetupTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class PostTypeSetupTest extends BaseTest {

	/**
	 * Post type setup interface.
	 *
	 * @var PostTypeSetup
	 * @since 1.0.0
	 */
	public PostTypeSetup $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = PostTypeSetup::exec();
	}

	public function test_register() {
		$bool = $this->obj->register();
		$this->assertIsBool( $bool );

		$bool = $this->obj->register();
		$this->assertFalse( $bool );
	}

	/**
	 * Test get post type list.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_post_type_list() {
		$types = $this->obj->get_post_type_list();
		$this->assertIsArray( $types );
	}

	/**
	 * Test get post type.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function test_get_post_type() {
		$this->expectException( PluginException::class );
		$this->obj->get_post_type( 'BlahBlah' );
	}

	public function test_get_post_type_taxonomies() {
		$types = $this->obj->get_post_type_list();
		$this->assertIsArray( $types );

		if ( isset( $types[0] ) ) {
			$type = $types[0];

			$objPostType = $this->obj->get_post_type( $type );
			$this->assertInstanceOf( PostTypeReg::class, $objPostType );

			$taxonomies = $this->obj->get_post_type_taxonomies( $type );
			if ( isset( $taxonomies ) ) {
				$this->assertIsArray( $taxonomies );
			}
		}

		$result = $this->obj->get_post_type_taxonomies( 'blah-blah' );
		$this->assertNull( $result );
	}

	public function test_add_remove() {
		global $wp_post_types;

		$pt = DRPPSM_PT_SERMON;

		$exist = post_type_exists( $pt );

		if ( ! $exist ) {
			$result = $this->obj->add();
			$this->assertIsArray( $result );
		} else {
			$result = $this->obj->remove();
			$this->assertIsArray( $result );
		}

		$exist = post_type_exists( $pt );

		if ( $exist ) {
			$result = $this->obj->remove();
			$this->assertIsArray( $result );
		} else {
			$result = $this->obj->remove();
			$this->assertIsArray( $result );
		}

		$this->obj->add();
		$exist = post_type_exists( $pt );
		$this->assertTrue( $exist );

		$result = $this->obj->flush();
		$this->assertNull( null );
	}

	public function testGetWpErrorMessage() {
		$types = $this->obj->get_post_type_list();
		$this->assertIsArray( $types );

		if ( isset( $types[0] ) ) {
			$type = $types[0];

			$objPostType = $this->obj->get_post_type( $type );
			$this->assertInstanceOf( PostTypeReg::class, $objPostType );

		}
	}
}
