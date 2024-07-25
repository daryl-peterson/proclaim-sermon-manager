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
class SermonPostTypeReg
{
    private array $postTypeArgs;

    public const POST_TYPE = 'drp_sermon';

    public function __construct()
    {
        $permalinks = PermaLinkStructure::get();

        $this->postTypeArgs = [
            'labels' => [
                'name' => __('Sermons', DOMAIN),
                'singular_name' => __('Sermon', DOMAIN),
                'all_items' => __('Sermons', DOMAIN),
                'menu_name' => _x('Sermons', 'menu', DOMAIN),
                'add_new' => __('Add New', DOMAIN),
                'add_new_item' => __('Add new sermon', DOMAIN),
                'edit' => __('Edit', DOMAIN),
                'edit_item' => __('Edit sermon', DOMAIN),
                'new_item' => __('New sermon', DOMAIN),
                'view' => __('View sermon', DOMAIN),
                'view_item' => __('View sermon', DOMAIN),
                'search_items' => __('Search sermon', DOMAIN),
                'not_found' => __('No sermons found', DOMAIN),
                'not_found_in_trash' => __('No sermons found in trash', DOMAIN),
                'featured_image' => __('Sermon image', DOMAIN),
                'set_featured_image' => __('Set sermon image', DOMAIN),
                'remove_featured_image' => __('Remove sermon image', DOMAIN),
                'use_featured_image' => __('Use as sermon image', DOMAIN),
                'insert_into_item' => __('Insert to sermon', DOMAIN),
                'uploaded_to_this_item' => __('Uploaded to this sermon', DOMAIN),
                'filter_items_list' => __('Filter sermon', DOMAIN),
                'items_list_navigation' => __('Sermon navigation', DOMAIN),
                'items_list' => __('Sermon list', DOMAIN),
            ],
            'public' => true,
            'show_ui' => true,
            'capability_type' => self::POST_TYPE,
            'capabilities' => [
                'manage_wpfc_categories' => 'manage_wpfc_categories',
                'manage_wpfc_sm_settings' => 'manage_wpfc_sm_settings',
            ],
            'map_meta_cap' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-sermon-manager',
            'hierarchical' => false,
            'rewrite' => [
                'slug' => $permalinks[self::POST_TYPE],
                'with_front' => false,
            ],
            'query_var' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'has_archive' => true,
            'supports' => [
                'title',
                'thumbnail',
                'publicize',
                'wpcom-markdown',
                'comments',
                'entry-views',
                'elementor',
                'excerpt',
                'revisions',
                'author',
                'editor',
            ],
        ];
    }

    public function init(): SermonPostTypeReg
    {
        return $this;
    }

    public function register()
    {
        if (!is_blog_installed() || post_type_exists('wpfc_sermon')) {
            return;
        }

        $key = Helper::getKeyName('sermon_register');
        do_action($key);

        $permalinks = sm_get_permalink_structure();

        register_post_type(
            'wpfc_sermon',
            apply_filters(
                'sm_register_post_type_wpfc_sermon',
                [
                    'labels' => [
                        'name' => __('Sermons', DOMAIN),
                        'singular_name' => __('Sermon', DOMAIN),
                        'all_items' => __('Sermons', DOMAIN),
                        'menu_name' => _x('Sermons', 'menu', DOMAIN),
                        'add_new' => __('Add New', DOMAIN),
                        'add_new_item' => __('Add new sermon', DOMAIN),
                        'edit' => __('Edit', DOMAIN),
                        'edit_item' => __('Edit sermon', DOMAIN),
                        'new_item' => __('New sermon', DOMAIN),
                        'view' => __('View sermon', DOMAIN),
                        'view_item' => __('View sermon', DOMAIN),
                        'search_items' => __('Search sermon', DOMAIN),
                        'not_found' => __('No sermons found', DOMAIN),
                        'not_found_in_trash' => __('No sermons found in trash', DOMAIN),
                        'featured_image' => __('Sermon image', DOMAIN),
                        'set_featured_image' => __('Set sermon image', DOMAIN),
                        'remove_featured_image' => __('Remove sermon image', DOMAIN),
                        'use_featured_image' => __('Use as sermon image', DOMAIN),
                        'insert_into_item' => __('Insert to sermon', DOMAIN),
                        'uploaded_to_this_item' => __('Uploaded to this sermon', DOMAIN),
                        'filter_items_list' => __('Filter sermon', DOMAIN),
                        'items_list_navigation' => __('Sermon navigation', DOMAIN),
                        'items_list' => __('Sermon list', DOMAIN),
                    ],
                    'public' => true,
                    'show_ui' => true,
                    'capability_type' => 'wpfc_sermon',
                    'capabilities' => [
                        'manage_wpfc_categories' => 'manage_wpfc_categories',
                        'manage_wpfc_sm_settings' => 'manage_wpfc_sm_settings',
                    ],
                    'map_meta_cap' => true,
                    'publicly_queryable' => true,
                    'exclude_from_search' => false,
                    'show_in_menu' => true,
                    'menu_icon' => 'dashicons-sermon-manager',
                    'hierarchical' => false,
                    'rewrite' => [
                        'slug' => $permalinks['wpfc_sermon'],
                        'with_front' => false,
                    ],
                    'query_var' => true,
                    'show_in_nav_menus' => true,
                    'show_in_rest' => true,
                    'has_archive' => true,
                    'supports' => [
                        'title',
                        'thumbnail',
                        'publicize',
                        'wpcom-markdown',
                        'comments',
                        'entry-views',
                        'elementor',
                        'excerpt',
                        'revisions',
                        'author',
                        'editor',
                    ],
                ]
            )
        );

        do_action('sm_after_register_post_type');
    }
}
