<?php

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\TextDomainInterface;

/**
 * Class description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class TextDomain implements TextDomainInterface
{
    public const INIT_KEY = 'TEXT_DOMAIN_INIT';

    public static function init(): TextDomainInterface
    {
        $obj = new self();

        $hook = Helper::getKeyName(self::INIT_KEY);

        if (did_action($hook) && !defined('PHPUNIT_TESTING')) {
            // @codeCoverageIgnoreStart
            return $obj;
            // @codeCoverageIgnoreEnd
        }

        add_action('init', [$obj, 'loadDomain']);
        Logger::debug('PLUGIN HOOKS INITIALIZED');
        do_action($hook);

        return $obj;
    }

    public function loadDomain(): void
    {
        load_plugin_textdomain(DOMAIN, false, basename(dirname(FILE)).'/languages/');
    }

    public function switchToSiteLocale(): void
    {
        try {
            if (!function_exists('switch_to_locale')) {
                return;
            }
            switch_to_locale(get_locale());

            // Filter on plugin_locale so load_plugin_textdomain loads the correct locale.
            add_filter('plugin_locale', 'get_locale');

            // Init Sermon Manager locale.
            $this->loadDomain();
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
        }
    }

    public function restoreLocale(): void
    {
        try {
            if (!function_exists('restore_previous_locale')) {
                return;
            }
            restore_previous_locale();

            // Remove filter.
            remove_filter('plugin_locale', 'get_locale');

            // Init Sermon Manager locale.
            $this->loadDomain();
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
        }
    }
}
