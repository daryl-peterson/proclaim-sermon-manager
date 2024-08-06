<?php
/**
 * Post type setup test.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Tests;

use DRPSermonManager\App;
use DRPSermonManager\Constants\PT;
use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Interfaces\PostTypeSetupInt;

/**
 * Post type setup test.
 */
class PostTypeSetupTest extends BaseTest {

	public PostTypeSetupInt $obj;

	public function setup(): void {
		$this->obj = $this->app->get( PostTypeSetupInt::class );
	}

	public function testGetPostTypes() {
		$types = $this->obj->get_post_type_list();
		$this->assertIsArray( $types );
	}

	public function testGetPosttype() {
		$this->expectException( PluginException::class );
		$this->obj->get_post_type( 'BlahBlah' );
	}

	public function testGetPostTypeTaxonomies() {
		$types = $this->obj->get_post_type_list();
		$this->assertIsArray( $types );

		if ( isset( $types[0] ) ) {
			$type = $types[0];

			$objPostType = $this->obj->get_post_type( $type );
			$this->assertInstanceOf( PostTypeRegInt::class, $objPostType );

			$taxonomies = $this->obj->get_post_type_taxonomies( $type );
			if ( isset( $taxonomies ) ) {
				$this->assertIsArray( $taxonomies );
			}
		}

		$result = $this->obj->get_post_type_taxonomies( 'blah-blah' );
		$this->assertNull( $result );
	}

	public function testAddRemove() {
		global $wp_post_types;

		$pt = PT::SERMON;

		$this->obj->remove();
		$this->obj->add();
		$this->obj->remove();

		$result = $this->obj->add();
		$this->assertNull( $result );

		$exist = post_type_exists( $pt );
		$this->assertTrue( $exist );

		$result = $this->obj->flush();
		$this->assertNull( $result );
	}

	public function testGetWpErrorMessage() {
		$types = $this->obj->get_post_type_list();
		$this->assertIsArray( $types );

		if ( isset( $types[0] ) ) {
			$type = $types[0];

			$objPostType = $this->obj->get_post_type( $type );
			$this->assertInstanceOf( PostTypeRegInt::class, $objPostType );

		}
	}
}
