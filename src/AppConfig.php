<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Interfaces\LogFormatterInterface;
use DRPSermonManager\Core\Interfaces\NoticeInterface;
use DRPSermonManager\Core\Interfaces\OptionsInterface;
use DRPSermonManager\Core\Interfaces\PluginInterface;
use DRPSermonManager\Core\Interfaces\RequirementsInterface;

/**
 * App configuration.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 */
class AppConfig
{
    public static array $vimeo;

    public static function get(): array
    {
        self::$vimeo = self::getVimeoSettings();

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

            AdminPage::class => function () {
                return new AdminPage();
            },
        ];
    }

    public static function getVimeoSettings(bool $force = false)
    {
        if (!$force) {
            if (isset(self::$vimeo)) {
                return self::$vimeo;
            }
        }

        $options = Options::getInstance();
        $settings = $options->get('settings');

        if (!isset($settings)) {
            // @codeCoverageIgnoreStart
            $settings = [
                'client_id' => 'Client ID',
                'client_secret' => 'Client Secret',
                'access_token' => 'Access Token',
                'match' => '',
            ];
            // @codeCoverageIgnoreEnd
        }

        return $settings;
    }
}
