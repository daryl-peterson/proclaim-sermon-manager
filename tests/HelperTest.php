<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Helper;

use const DRPSermonManager\KEY_PREFIX;

class HelperTest extends BaseTest
{
    public function testMisc()
    {
        $result = Helper::getKeyName('blah');
        $this->assertIsString($result);

        $key = Helper::getKeyName('_blah');
        $result = Helper::getKeyName(KEY_PREFIX.'_blah');
        $this->assertIsString($result);
        $this->assertEquals($key, $result);

        $result = Helper::getPluginDir();
        $this->assertIsString($result);

        $result = Helper::getUrl();
        $this->assertIsString($result);

        $result = Helper::getSlug();
        $this->assertIsString($result);

        $result = Helper::isCompatible();
        $this->assertIsBool($result);

        $result = Helper::isPluginActive('blah');
        $this->assertFalse($result);

        $result = Helper::getActivePlugins();
        $this->assertIsArray($result);

        $key = Helper::getKeyName('delete_me');
        delete_transient($key);

        $result = Helper::setTransient('delete_me', true, 10);
        $this->assertTrue($result);

        $result = Helper::getTransient('delete_me');
        $this->assertTrue($result);

        $result = Helper::GmtToLocal(gmdate(DATE_ISO8601));
        $this->assertNotNull($result);
    }

    public function testGetConfig()
    {
        $this->expectException(PluginException::class);
        Helper::getConfig('blah-blah');
    }
}
