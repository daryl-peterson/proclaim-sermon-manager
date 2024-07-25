<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\App;
use DRPSermonManager\Logger;

class LoggerTest extends BaseTest
{
    public function testLogger()
    {
        $result = Logger::Info(['TEST' => 'INFO']);
        $this->assertIsBool($result);
        $this->assertTrue($result);

        $obj = new \WP_Error('BAD', 'MESSAGE');
        $result = Logger::error($obj);
        $this->assertTrue($result);

        $result = Logger::debug('DEBUG TEST');
        $this->assertTrue($result);

        $result = App::getLogFormatterInt();
        $this->assertNotNull($result);

        $result = App::getOptionsInt();
        $this->assertNotNull($result);

        $result = App::getRequirementsInt();
        $this->assertNotNull($result);
    }
}
