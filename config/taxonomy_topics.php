<?php

namespace DRPSermonManager;

$permalinks = App::getPermalinkStructureInt()->get();
$tax = Constant::TAX_TOPICS;
$capabilities = [
    'manage_terms' => Constant::CAP_MANAGE_CATAGORIES,
    'edit_terms' => Constant::CAP_MANAGE_CATAGORIES,
    'delete_terms' => Constant::CAP_MANAGE_CATAGORIES,
    'assign_terms' => Constant::CAP_MANAGE_CATAGORIES,
];

return [
    'hierarchical' => false,
    'label' => __('Topics', DOMAIN),
    'labels' => [
        'name' => __('Topics', DOMAIN),
        'singular_name' => __('Topic', DOMAIN),
        'menu_name' => _x('Topics', 'menu', DOMAIN),
        'search_items' => __('Search topics', DOMAIN),
        'all_items' => __('All topics', DOMAIN),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit topic', DOMAIN),
        'update_item' => __('Update topic', DOMAIN),
        'add_new_item' => __('Add new topic', DOMAIN),
        'new_item_name' => __('New topic name', DOMAIN),
        'not_found' => __('No topics found', DOMAIN),
    ],
    'show_ui' => true,
    'query_var' => true,
    'show_in_rest' => true,
    'rewrite' => [
        'slug' => $permalinks[$tax],
        'with_front' => false,
    ],
    'capabilities' => $capabilities,
];
