<?php

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\Initable;
use DRPSermonManager\Interfaces\LogFormatterInt;
use DRPSermonManager\Interfaces\NoticeInt;
use DRPSermonManager\Interfaces\OptionsInt;
use DRPSermonManager\Interfaces\PermaLinkStructureInt;
use DRPSermonManager\Interfaces\PluginInt;
use DRPSermonManager\Interfaces\PostTypeSetupInt;
use DRPSermonManager\Interfaces\RequirementCheckInt;
use DRPSermonManager\Interfaces\RequirementsInt;
use DRPSermonManager\Interfaces\RolesInt;
use DRPSermonManager\Interfaces\TextDomainInt;
use DRPSermonManager\Logging\LogFormatter;
use DRPSermonManager\Traits\SingletonTrait;

/**
 * App service locator / facade.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class App implements Initable
{
    use SingletonTrait;

    public static function init(): App
    {
        return App::getInstance();
    }

    /**
     * Get admin page.
     */
    public static function getAdminPage(): AdminPage
    {
        return new AdminPage();
    }

    public static function getLogFormatterInt(): LogFormatterInt
    {
        return new LogFormatter();
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
        return PermaLinks::init();
    }

    /**
     * Get Plugin interface.
     */
    public static function getPluginInt(): PluginInt
    {
        return new Plugin();
    }

    public static function getPostTypeSetupInt(): PostTypeSetupInt
    {
        return PostTypeSetup::init();
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
