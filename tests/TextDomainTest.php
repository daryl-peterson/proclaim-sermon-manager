<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\Core\Interfaces\TextDomainInterface;
use DRPSermonManager\TextDomain;

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
class TextDomainTest extends BaseTest
{
    public TextDomainInterface $obj;

    public function setup(): void
    {
        $this->obj = TextDomain::init();
    }

    public function testBlah()
    {
        $result = $this->obj->blah();
        $this->assertNull($result);
    }
}
