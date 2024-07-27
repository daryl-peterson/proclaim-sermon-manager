<?php

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\LogFormatterInt;
use DRPSermonManager\Interfaces\PluginInt;

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
            LogFormatterInt::class => function () {
                return new LogFormatter();
            },

            PluginInt::class => function () {
                return new Plugin();
            },

            AdminPage::class => function () {
                return new AdminPage();
            },
        ];
    }
}
