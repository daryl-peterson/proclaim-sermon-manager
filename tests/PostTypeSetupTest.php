<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\App;
use DRPSermonManager\Constant;
use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Interfaces\PostTypeSetupInt;

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
class PostTypeSetupTest extends BaseTest
{
    public PostTypeSetupInt $obj;

    public function setup(): void
    {
        $this->obj = App::getPostTypeSetupInt();
    }

    public function testGetPostTypes()
    {
        $types = $this->obj->getPostTypeList();
        $this->assertIsArray($types);
    }

    public function testGetPosttype()
    {
        $this->expectException(PluginException::class);
        $this->obj->getPostType('BlahBlah');
    }

    public function testGetPostTypeTaxonomies()
    {
        $types = $this->obj->getPostTypeList();
        $this->assertIsArray($types);

        if (isset($types[0])) {
            $type = $types[0];

            $objPostType = $this->obj->getPostType($type);
            $this->assertInstanceOf(PostTypeRegInt::class, $objPostType);

            $taxonomies = $this->obj->getPostTypeTaxonomies($type);
            if (isset($taxonomies)) {
                $this->assertIsArray($taxonomies);
            }
        }

        $result = $this->obj->getPostTypeTaxonomies('blah-blah');
        $this->assertNull($result);
    }

    public function testAddRemove()
    {
        global $wp_post_types;

        $pt = Constant::POST_TYPE_SERMON;

        $this->obj->remove();
        $this->obj->add();
        $this->obj->remove();

        $result = $this->obj->add();
        $this->assertNull($result);

        $exist = post_type_exists($pt);
        $this->assertTrue($exist);

        $result = $this->obj->flush();
        $this->assertNull($result);
    }

    public function testGetWpErrorMessage()
    {
        $types = $this->obj->getPostTypeList();
        $this->assertIsArray($types);

        if (isset($types[0])) {
            $type = $types[0];

            $objPostType = $this->obj->getPostType($type);
            $this->assertInstanceOf(PostTypeRegInt::class, $objPostType);

            $error = new \WP_Error('This is a test WP Error');
            $result = $objPostType->getWpErrorMessage($error);
            $this->assertIsString($result);
        }
    }
}
