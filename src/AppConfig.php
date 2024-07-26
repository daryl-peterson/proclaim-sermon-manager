<?php

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\LogFormatterInterface;
use DRPSermonManager\Interfaces\NoticeInterface;
use DRPSermonManager\Interfaces\OptionsInterface;
use DRPSermonManager\Interfaces\PluginInterface;
use DRPSermonManager\Interfaces\RequirementsInterface;
use DRPSermonManager\Interfaces\TextDomainInterface;

/**
 * App configuration.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 */
class AppConfig
{
    public static function get(): array
    {
        return [
            LogFormatterInterface::class => function () {
                return new LogFormatter();
            },

            NoticeInterface::class => function () {
                return Notice::getInstance();
            },

            OptionsInterface::class => function () {
                return Options::getInstance();
            },

            PluginInterface::class => function () {
                return new Plugin();
            },

            RequirementsInterface::class => function (NoticeInterface $notice) {
                return new Requirements($notice);
            },

            TextDomainInterface::class => function () {
                return TextDomain::init();
            },

            AdminPage::class => function () {
                return new AdminPage();
            },
        ];
    }
}
