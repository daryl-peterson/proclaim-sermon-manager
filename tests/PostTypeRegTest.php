<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\App;
use DRPSermonManager\Constant;
use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Logger;

use const DRPSermonManager\FILE;

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
class PostTypeRegTest extends BaseTest
{
    public PostTypeRegInt $obj;

    public function setup(): void
    {
        $this->obj = App::getPostTypeRegInt();
    }

    public function tester()
    {
        global $wp_post_types;

        $pt = Constant::POST_TYPE_SERMON;
        if (post_type_exists($pt)) {
            $result = $this->obj->remove();
            $this->assertNull($result);
        }
        if (!post_type_exists($pt)) {
            $result = $this->obj->add();
            $this->assertNull($result);
        }
        // Logger::debug(['POST_TYPES' => ['WPFC' => $wp_post_types['wpfc_sermon'], 'DRP' => $wp_post_types[$pt]]]);

        activate_plugin(FILE);
    }

    private function add()
    {
    }
}
