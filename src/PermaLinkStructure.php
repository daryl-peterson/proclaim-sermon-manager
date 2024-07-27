<?php

namespace DRPSermonManager;

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
class PermaLinkStructure implements PermaLinkStructureInt
{
    use SingletonTrait;

    private array $permalinks;

    public static function init(): PermaLinkStructureInt
    {
        $obj = PermaLinkStructure::getInstance();
        $obj->config();

        return $obj;
    }

    public function config()
    {
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

        $perm = wp_parse_args((array) get_option('sm_permalinks', []),
            [
                Constant::TAX_PREACHER => trim(sanitize_title($opts->get(Constant::TAX_PREACHER, ''))),
                Constant::TAX_SERIES => '',
                Constant::TAX_TOPICS => '',
                Constant::TAX_BIBLE_BOOK => '',
                Constant::TAX_SERVICE_TYPE => trim(sanitize_title($opts->get('service_type_label', ''))),
                Constant::POST_TYPE_SERMON => trim($opts->get('archive_slug', '')),
                'use_verbose_page_rules' => false,
            ]);

        // Ensure rewrite slugs are set.
        $perm[Constant::TAX_PREACHER] = empty($perm[Constant::TAX_PREACHER]) ?
            _x('preacher', 'slug', DOMAIN) : $perm[Constant::TAX_PREACHER];

        $perm[Constant::TAX_SERIES] = empty($perm[Constant::TAX_SERIES]) ?
            _x('series', 'slug', DOMAIN) : $perm[Constant::TAX_SERIES];

        $perm[Constant::TAX_TOPICS] = empty($perm[Constant::TAX_TOPICS]) ?
            _x('topics', 'slug', DOMAIN) : $perm[Constant::TAX_TOPICS];

        $perm[Constant::TAX_BIBLE_BOOK] = empty($perm[Constant::TAX_BIBLE_BOOK]) ?
            _x('book', 'slug', DOMAIN) : $perm[Constant::TAX_BIBLE_BOOK];

        $perm[Constant::TAX_SERVICE_TYPE] = empty($perm[Constant::TAX_SERVICE_TYPE]) ?
            _x('service-type', 'slug', DOMAIN) : $perm[Constant::TAX_SERVICE_TYPE];

        $perm[Constant::POST_TYPE_SERMON] = empty($perm[Constant::POST_TYPE_SERMON]) ?
            _x('sermons', 'slug', DOMAIN) : $perm[Constant::POST_TYPE_SERMON];

        foreach ($perm as $key => $value) {
            $perm[$key] = untrailingslashit($value);
        }

        // @todo fix
        if ($opts->get('common_base_slug')) {
            foreach ($perm as $name => &$permalink) {
                if (Constant::POST_TYPE_SERMON === $name) {
                    continue;
                }

                $permalink = $perm[Constant::POST_TYPE_SERMON].'/'.$permalink;
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

    public function get(): array
    {
        return $this->permalinks;
    }
}
