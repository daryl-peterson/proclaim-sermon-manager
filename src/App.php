<?php

namespace DRPSermonManager;

use DI\Container;
use DRPSermonManager\Interfaces\LogFormatterInt;
use DRPSermonManager\Interfaces\NoticeInt;
use DRPSermonManager\Interfaces\OptionsInt;
use DRPSermonManager\Interfaces\PermaLinkStructureInt;
use DRPSermonManager\Interfaces\PluginInt;
use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Interfaces\RequirementCheckInt;
use DRPSermonManager\Interfaces\RequirementsInt;
use DRPSermonManager\Interfaces\RolesInt;
use DRPSermonManager\Interfaces\TextDomainInt;
use DRPSermonManager\Traits\SingletonTrait;

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
        try {
            if (!isset($this->container) || defined('PHPUNIT_TESTING')) {
                $config = AppConfig::get();
                $this->container = new Container($config);
            }

            return $this;
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            error_log(print_r($th, true));
            // @codeCoverageIgnoreEnd
        }
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

    public static function getLogFormatterInt(): LogFormatterInt
    {
        return self::getObject(LogFormatterInt::class);
    }

    /**
     * Get notice interface.
     */
    public static function getNoticeInt(): NoticeInt
    {
        return Notice::init();
    }

    public static function getOptionsInt(): OptionsInt
    {
        return Options::init();
    }

    public static function getPermalinkStructureInt(): PermaLinkStructureInt
    {
        return PermaLinkStructure::init();
    }

    /**
     * Get Plugin interface.
     */
    public static function getPluginInt(): PluginInt
    {
        return self::getObject(PluginInt::class);
    }

    public static function getPostTypeRegInt(): PostTypeRegInt
    {
        return PostTypeReg::init();
    }

    /**
     * Get requirements interface.
     */
    public static function getRequirementsInt(): RequirementsInt
    {
        return Requirements::init();
    }

    public static function getRequirementCheckInt(): RequirementCheckInt
    {
        return RequirementCheck::init();
    }

    public static function getRolesInt(): RolesInt
    {
        return Roles::init();
    }

    public static function getTextDomainInt(): TextDomainInt
    {
        return TextDomain::init();
    }
}
