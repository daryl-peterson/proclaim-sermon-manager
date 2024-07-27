<?php

namespace DRPSermonManager\Tests;

use DI\Container;
use DRPSermonManager\App;
use DRPSermonManager\Interfaces\NoticeInt;
use DRPSermonManager\Logger;

class NoticeTest extends BaseTest
{
    public function tester()
    {
        $title = 'This is the tile';
        $message = 'This is the message';

        $result = App::getContainer();
        $this->assertInstanceOf(Container::class, $result);

        $obj = App::getNoticeInt(NoticeInt::class);
        $obj->setSuccess($title, $message);
        $obj->setWarning($title, $message);
        $obj->setInfo($title, $message);
        $obj->setError($title, $message);

        ob_start();
        $obj->showNotice();
        $result = ob_get_clean();
        Logger::debug($result);
        $this->assertIsString($result);

        $obj->delete();

        $obj->showNotice();
    }
}
