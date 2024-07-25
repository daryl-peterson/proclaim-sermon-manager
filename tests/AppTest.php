<?php

namespace DRPSermonManager\Tests;

use DI\Container;
use DRPSermonManager\AdminPage;
use DRPSermonManager\App;
use DRPSermonManager\Plugin;

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
class AppTest extends BaseTest
{
    public App $obj;

    public function setup(): void
    {
        $this->obj = App::getInstance();
        $this->obj->init();
    }

    public function testGetInstance()
    {
        $this->assertNotNull($this->obj);
    }

    public function testGetContainer()
    {
        $container = $this->obj->getContainer();
        $this->assertInstanceOf(Container::class, $container);
    }

    public function testGetPluginInt()
    {
        $plugin = $this->obj->getPluginInt();
        $this->assertInstanceOf(Plugin::class, $plugin);
    }

    public function testGetAdminPage()
    {
        $result = $this->obj->getAdminPage();
        $this->assertInstanceOf(AdminPage::class, $result);
    }
}
