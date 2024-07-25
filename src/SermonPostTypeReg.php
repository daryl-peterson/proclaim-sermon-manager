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

    public function __construct()
    {
        $this->postTypeArgs = [
            'labels' => [
                'name' => __('Sermons', 'sermon-manager-for-wordpress'),
                'singular_name' => __('Sermon', 'sermon-manager-for-wordpress'),
                'all_items' => __('Sermons', 'sermon-manager-for-wordpress'),
                'menu_name' => _x('Sermons', 'menu', 'sermon-manager-for-wordpress'),
                'add_new' => __('Add New', 'sermon-manager-for-wordpress'),
                'add_new_item' => __('Add new sermon', 'sermon-manager-for-wordpress'),
                'edit' => __('Edit', 'sermon-manager-for-wordpress'),
                'edit_item' => __('Edit sermon', 'sermon-manager-for-wordpress'),
                'new_item' => __('New sermon', 'sermon-manager-for-wordpress'),
                'view' => __('View sermon', 'sermon-manager-for-wordpress'),
                'view_item' => __('View sermon', 'sermon-manager-for-wordpress'),
                'search_items' => __('Search sermon', 'sermon-manager-for-wordpress'),
                'not_found' => __('No sermons found', 'sermon-manager-for-wordpress'),
                'not_found_in_trash' => __('No sermons found in trash', 'sermon-manager-for-wordpress'),
                'featured_image' => __('Sermon image', 'sermon-manager-for-wordpress'),
                'set_featured_image' => __('Set sermon image', 'sermon-manager-for-wordpress'),
                'remove_featured_image' => __('Remove sermon image', 'sermon-manager-for-wordpress'),
                'use_featured_image' => __('Use as sermon image', 'sermon-manager-for-wordpress'),
                'insert_into_item' => __('Insert to sermon', 'sermon-manager-for-wordpress'),
                'uploaded_to_this_item' => __('Uploaded to this sermon', 'sermon-manager-for-wordpress'),
                'filter_items_list' => __('Filter sermon', 'sermon-manager-for-wordpress'),
                'items_list_navigation' => __('Sermon navigation', 'sermon-manager-for-wordpress'),
                'items_list' => __('Sermon list', 'sermon-manager-for-wordpress'),
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
                        'name' => __('Sermons', 'sermon-manager-for-wordpress'),
                        'singular_name' => __('Sermon', 'sermon-manager-for-wordpress'),
                        'all_items' => __('Sermons', 'sermon-manager-for-wordpress'),
                        'menu_name' => _x('Sermons', 'menu', 'sermon-manager-for-wordpress'),
                        'add_new' => __('Add New', 'sermon-manager-for-wordpress'),
                        'add_new_item' => __('Add new sermon', 'sermon-manager-for-wordpress'),
                        'edit' => __('Edit', 'sermon-manager-for-wordpress'),
                        'edit_item' => __('Edit sermon', 'sermon-manager-for-wordpress'),
                        'new_item' => __('New sermon', 'sermon-manager-for-wordpress'),
                        'view' => __('View sermon', 'sermon-manager-for-wordpress'),
                        'view_item' => __('View sermon', 'sermon-manager-for-wordpress'),
                        'search_items' => __('Search sermon', 'sermon-manager-for-wordpress'),
                        'not_found' => __('No sermons found', 'sermon-manager-for-wordpress'),
                        'not_found_in_trash' => __('No sermons found in trash', 'sermon-manager-for-wordpress'),
                        'featured_image' => __('Sermon image', 'sermon-manager-for-wordpress'),
                        'set_featured_image' => __('Set sermon image', 'sermon-manager-for-wordpress'),
                        'remove_featured_image' => __('Remove sermon image', 'sermon-manager-for-wordpress'),
                        'use_featured_image' => __('Use as sermon image', 'sermon-manager-for-wordpress'),
                        'insert_into_item' => __('Insert to sermon', 'sermon-manager-for-wordpress'),
                        'uploaded_to_this_item' => __('Uploaded to this sermon', 'sermon-manager-for-wordpress'),
                        'filter_items_list' => __('Filter sermon', 'sermon-manager-for-wordpress'),
                        'items_list_navigation' => __('Sermon navigation', 'sermon-manager-for-wordpress'),
                        'items_list' => __('Sermon list', 'sermon-manager-for-wordpress'),
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
