<?php

namespace DRPSermonManager;

use DRPSermonManager\Constants\CAP;
use DRPSermonManager\Constants\TAX;

$permalinks = App::getPermalinkStructureInt()->get();
$tax = TAX::SERIES;
$capabilities = [
    'manage_terms' => CAP::MANAGE_CATAGORIES,
    'edit_terms' => CAP::MANAGE_CATAGORIES,
    'delete_terms' => CAP::MANAGE_CATAGORIES,
    'assign_terms' => CAP::MANAGE_CATAGORIES,
];

return [
    'hierarchical' => false,
    'label' => __('Series', DOMAIN),
    'labels' => [
        'name' => __('Series', DOMAIN),
        'singular_name' => __('Series', DOMAIN),
        'menu_name' => _x('Series', 'menu', DOMAIN),
        'search_items' => __('Search series', DOMAIN),
        'all_items' => __('All series', DOMAIN),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit series', DOMAIN),
        'update_item' => __('Update series', DOMAIN),
        'add_new_item' => __('Add new series', DOMAIN),
        'new_item_name' => __('New series name', DOMAIN),
        'not_found' => __('No series found', DOMAIN),
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
