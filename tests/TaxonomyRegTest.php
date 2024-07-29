<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\Interfaces\TaxonomyRegInt;
use DRPSermonManager\TaxPreacherReg;

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
class TaxonomyRegTest extends BaseTest
{
    public function testGetWpErrorMessage()
    {
        $preacher = TaxPreacherReg::init();
        $this->assertInstanceOf(TaxonomyRegInt::class, $preacher);

        $error = new \WP_Error('This is a test WP Error');
        $result = $preacher->getWpErrorMessage($error);
        $this->assertIsString($result);
    }
}
