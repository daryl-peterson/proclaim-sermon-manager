<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\FatalError;
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
class DeactivatorTest extends BaseTest
{
    public function testMisc()
    {
        try {
            $this->forceException();
        } catch (\Throwable $th) {
            FatalError::set($th);
        }

        $result = Helper::isPluginActive(plugin_basename(FILE));
        $this->assertFalse($result);

        activate_plugin(plugin_basename(FILE));
    }

    private function forceException()
    {
        throw new PluginException('Test Force Exception');
    }
}
