<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\App;
use DRPSermonManager\Logger;

class LoggerTest extends BaseTest
{
    public function testLogger()
    {
        $result = Logger::Info(['TEST' => 'INFO']);
        $this->assertTrue($result);

        $obj = new \WP_Error('BAD', 'MESSAGE');

        $result = Logger::error($obj);

        $result = App::getLogFormatterInt();
        $this->assertNotNull($result);
    }
}
