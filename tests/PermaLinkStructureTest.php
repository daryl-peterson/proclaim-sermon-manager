<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\App;
use DRPSermonManager\Interfaces\PermaLinkStructureInt;
use DRPSermonManager\Logging\Logger;

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
class PermaLinkStructureTest extends BaseTest
{
    public PermaLinkStructureInt $obj;

    public function setup(): void
    {
        $this->obj = App::getPermalinkStructureInt();
    }

    public function testPermaLinkStructure()
    {
        $result = $this->obj->get();
        $this->assertIsArray($result);

        Logger::debug($result);
        $this->obj->get();
    }
}
