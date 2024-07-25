<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Interfaces\TextDomainInterface;

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
    public static function init(): TextDomainInterface
    {
        $obj = new self();

        $hook = Helper::getKeyName('TEXT_DOMAIN_INIT');

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

    public function loadDomain()
    {
        load_plugin_textdomain('drp_sermon', false, basename(dirname(FILE)).'/languages/');
    }

    public function blah()
    {
        Logger::debug('TESTING BLAH');
    }
}
