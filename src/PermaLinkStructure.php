<?php

namespace DRPSermonManager;

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
class PermaLinkStructure
{
    public static function get(): array
    {
        $opts = App::getOptionsInt();
        if (did_action('admin_init')) {
            TextDomain::init()->switchToSiteLocale();
        }

        $permalinks = wp_parse_args((array) get_option('sm_permalinks', []), [
            'wpfc_preacher' => trim(sanitize_title($opts->get('preacher_label'))),
            'wpfc_sermon_series' => '',
            'wpfc_sermon_topics' => '',
            'wpfc_bible_book' => '',
            'wpfc_service_type' => trim(sanitize_title(\SermonManager::getOption('service_type_label'))),
            'wpfc_sermon' => trim(\SermonManager::getOption('archive_slug')),
            'use_verbose_page_rules' => false,
        ]);

        // Ensure rewrite slugs are set.
        $permalinks[SermonPostTypeReg::POST_TYPE] = untrailingslashit(empty($permalinks['wpfc_preacher']) ? _x('preacher', 'slug', DOMAIN) : $permalinks['wpfc_preacher']);
        $permalinks['wpfc_sermon_series'] = untrailingslashit(empty($permalinks['wpfc_sermon_series']) ? _x('series', 'slug', DOMAIN) : $permalinks['wpfc_sermon_series']);
        $permalinks['wpfc_sermon_topics'] = untrailingslashit(empty($permalinks['wpfc_sermon_topics']) ? _x('topics', 'slug', DOMAIN) : $permalinks['wpfc_sermon_topics']);
        $permalinks['wpfc_bible_book'] = untrailingslashit(empty($permalinks['wpfc_bible_book']) ? _x('book', 'slug', DOMAIN) : $permalinks['wpfc_bible_book']);
        $permalinks['wpfc_service_type'] = untrailingslashit(empty($permalinks['wpfc_service_type']) ? _x('service-type', 'slug', DOMAIN) : $permalinks['wpfc_service_type']);
        $permalinks[SermonPostTypeReg::POST_TYPE] = untrailingslashit(empty($permalinks[SermonPostTypeReg::POST_TYPE]) ? _x('sermons', 'slug', DOMAIN) : $permalinks[SermonPostTypeReg::POST_TYPE]);

        if (\SermonManager::getOption('common_base_slug')) {
            foreach ($permalinks as $name => &$permalink) {
                if (SermonPostTypeReg::POST_TYPE === $name) {
                    continue;
                }

                $permalink = $permalinks[SermonPostTypeReg::POST_TYPE].'/'.$permalink;
            }
        }

        if (did_action('admin_init')) {
            TextDomain::init()->restoreLocale();
        }

        $hook = Helper::getKeyName('permalink_structure');

        /*
         * Allows to easily modify the slugs of sermons and taxonomies.
         *
         * @param array $permalinks Existing permalinks structure.
         *
         * @since 1.0.0
         */

        return apply_filters($hook, $permalinks);
    }
}
