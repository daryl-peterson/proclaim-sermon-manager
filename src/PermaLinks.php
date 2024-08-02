<?php

namespace DRPSermonManager;

use DRPSermonManager\Constants\PT;
use DRPSermonManager\Constants\TAX;
use DRPSermonManager\Interfaces\PermaLinkStructureInt;
use DRPSermonManager\Traits\SingletonTrait;

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
class PermaLinks implements PermaLinkStructureInt
{
    use SingletonTrait;

    private array $permalinks;

    public static function init(): PermaLinkStructureInt
    {
        $obj = PermaLinks::getInstance();
        $obj->config();

        return $obj;
    }

    public function get(): array
    {
        return $this->permalinks;
    }

    private function config(): void
    {
        if (isset($this->permalinks) && !defined('PHPUNIT_TESTING')) {
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }
        $actionKey = Helper::getKeyName('PERMALINK_CONFIG');
        if (did_action($actionKey) && !defined('PHPUNIT_TESTING')) {
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }

        $opts = App::getOptionsInt();
        if (did_action('admin_init')) {
            // @codeCoverageIgnoreStart
            TextDomain::init()->switchToSiteLocale();
            // @codeCoverageIgnoreEnd
        }

        $perm = wp_parse_args((array) $opts->get('permalinks', []),
            [
                TAX::PREACHER => trim(sanitize_title($opts->get(TAX::PREACHER, ''))),
                TAX::SERIES => '',
                TAX::TOPICS => '',
                TAX::BIBLE_BOOK => '',
                TAX::SERVICE_TYPE => trim(sanitize_title($opts->get('service_type_label', ''))),
                PT::SERMON => trim($opts->get('archive_slug', '')),
                'use_verbose_page_rules' => false,
            ]);

        // Ensure rewrite slugs are set.
        $perm[TAX::PREACHER] = empty($perm[TAX::PREACHER]) ?
            _x('preacher', 'slug', DOMAIN) : $perm[TAX::PREACHER];

        $perm[TAX::SERIES] = empty($perm[TAX::SERIES]) ?
            _x('series', 'slug', DOMAIN) : $perm[TAX::SERIES];

        $perm[TAX::TOPICS] = empty($perm[TAX::TOPICS]) ?
            _x('topics', 'slug', DOMAIN) : $perm[TAX::TOPICS];

        $perm[TAX::BIBLE_BOOK] = empty($perm[TAX::BIBLE_BOOK]) ?
            _x('book', 'slug', DOMAIN) : $perm[TAX::BIBLE_BOOK];

        $perm[TAX::SERVICE_TYPE] = empty($perm[TAX::SERVICE_TYPE]) ?
            _x('service-type', 'slug', DOMAIN) : $perm[TAX::SERVICE_TYPE];

        $perm[PT::SERMON] = empty($perm[PT::SERMON]) ?
            _x('sermons', 'slug', DOMAIN) : $perm[PT::SERMON];

        foreach ($perm as $key => $value) {
            $perm[$key] = untrailingslashit($value);
        }

        // @todo fix
        if ($opts->get('common_base_slug')) {
            foreach ($perm as $name => &$permalink) {
                if (PT::SERMON === $name) {
                    continue;
                }

                $permalink = $perm[PT::SERMON].'/'.$permalink;
            }
        }

        if (did_action('admin_init')) {
            // @codeCoverageIgnoreStart
            TextDomain::init()->restoreLocale();
            // @codeCoverageIgnoreEnd
        }

        $hook = Helper::getKeyName('permalink_structure');

        /*
         * Allows to easily modify the slugs of sermons and taxonomies.
         *
         * @param array $perm Existing permalinks structure.
         * @since 1.0.0
         */
        $this->permalinks = apply_filters($hook, $perm);
        do_action($actionKey);
    }
}
