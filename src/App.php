<?php

namespace DRPSermonManager;

use DI\Container;
use DRPSermonManager\Core\Interfaces\LogFormatterInterface;
use DRPSermonManager\Core\Interfaces\NoticeInterface;
use DRPSermonManager\Core\Interfaces\OptionsInterface;
use DRPSermonManager\Core\Interfaces\PluginInterface;
use DRPSermonManager\Core\Interfaces\RequirementsInterface;
use DRPSermonManager\Core\Interfaces\SermonPostsInterface;
use DRPSermonManager\Core\Interfaces\SermonVideoInterface;
use DRPSermonManager\Core\Interfaces\VideoInterface;
use DRPSermonManager\Core\Traits\SingletonTrait;

/**
 * App service container.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class App
{
    use SingletonTrait;
    public Container $container;

    public function init(): App
    {
        if (!isset($this->container) || defined('PHPUNIT_TESTING')) {
            $config = AppConfig::get();
            $this->container = new Container($config);
        }

        return $this;
    }

    public static function getContainer(): Container
    {
        $obj = App::getInstance()->init();

        return $obj->container;
    }

    public static function getObject(string $object): mixed
    {
        $obj = App::getInstance()->init();

        return $obj->container->get($object);
    }

    /**
     * Get admin page.
     */
    public static function getAdminPage(): AdminPage
    {
        return self::getObject(AdminPage::class);
    }

    public static function getAlbumInt(): Album
    {
        return self::getObject(Album::class);
    }

    public static function getChannelInt(): Channel
    {
        return self::getObject(Channel::class);
    }

    public static function getLogFormatterInt(): LogFormatterInterface
    {
        return self::getObject(LogFormatterInterface::class);
    }

    /**
     * Get notice interface.
     */
    public static function getNoticeInt(): NoticeInterface
    {
        return self::getObject(NoticeInterface::class);
    }

    public static function getOptionsInt(): OptionsInterface
    {
        return self::getObject(OptionsInterface::class);
    }

    /**
     * Get Plugin interface.
     */
    public static function getPluginInt(): PluginInterface
    {
        return self::getObject(PluginInterface::class);
    }

    /**
     * Get requirements interface.
     */
    public static function getRequirementsInt(): RequirementsInterface
    {
        return self::getObject(RequirementsInterface::class);
    }

    /**
     * Get sermon post interface.
     */
    public static function getSermonPostInt(): SermonPostsInterface
    {
        return self::getObject(SermonPostsInterface::class);
    }

    public static function getSermonVideoInt(): SermonVideoInterface
    {
        return self::getObject(SermonVideoInterface::class);
    }

    public static function getUserInt(): User
    {
        return self::getObject(User::class);
    }

    public static function getVideoInt(): Video
    {
        return self::getObject(VideoInterface::class);
    }
}
