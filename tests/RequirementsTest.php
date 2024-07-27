<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\App;
use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\RequirementCheck;

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
class RequirementsTest extends BaseTest
{
    private RequirementCheck $obj;

    public function setup(): void
    {
        $this->obj = App::getRequirementCheckInt();
    }

    public function teardown(): void
    {
        $obj = App::getRequirementsInt();
        $obj->notice()->delete();
    }

    public function tester()
    {
        wp_set_current_user(1);

        $obj = App::getRequirementsInt();
        $result = $obj->isCompatible();
        $this->assertNull($result);
        $obj->isCompatible();
    }

    public function testPHPVer()
    {
        $this->expectException(PluginException::class);
        $this->obj->checkPHPVer('9.0');
    }

    public function testWPVer()
    {
        $this->expectException(PluginException::class);
        $this->obj->checkWPVer('7.0');
    }

    public function testForceFail()
    {
        wp_set_current_user(1);
        $obj = App::getRequirementsInt();
        $obj->setFail(true);
        $result = $obj->isCompatible();
        $this->assertNull($result);
    }
}
