<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\App;
use DRPSermonManager\Helper;
use DRPSermonManager\Options;

/**
 * Options test.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 */
class OptionsTest extends BaseTest
{
    public Options $obj;

    public function setup(): void
    {
        $this->obj = App::getOptionsInt();
    }

    public function testMethods()
    {
        $key = Helper::getKeyName('delete_me_now');
        $this->obj->delete($key);
        $result = $this->obj->set($key, true);
        $this->assertTrue($result);

        // Cover setting same value
        $result = $this->obj->set($key, true);
        $this->assertTrue($result);

        $result = $this->obj->get($key, false);
        $this->assertTrue($result);

        // Cover update
        $result = $this->obj->set($key, null);
        $this->assertTrue($result);

        $result = $this->obj->delete($key);
        $this->assertTrue($result);
    }
}
