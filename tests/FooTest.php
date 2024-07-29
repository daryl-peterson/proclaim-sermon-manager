<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\Logger;

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
class FooTest extends BaseTest
{
    public function tester()
    {
        $obj = true;
        $this->assertTrue($obj);

        $tax = \sm_get_taxonomies();
        $this->assertNotNull($tax);
        Logger::error(['TAX' => $tax]);
    }
}
