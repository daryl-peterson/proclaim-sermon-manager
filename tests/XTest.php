<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\Activator;
use DRPSermonManager\Helper;

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
class XTest extends BaseTest
{
    public function testMisc()
    {
        Activator::init()->run();

        $result = Helper::isPluginActive(plugin_basename(FILE));
        $this->assertTrue($result);
    }
}
