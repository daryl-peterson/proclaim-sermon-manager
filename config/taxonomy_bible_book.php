<?php

namespace DRPSermonManager;

$permalinks = App::getPermalinkStructureInt()->get();
$tax = Constant::TAX_BIBLE_BOOK;
$capabilities = [
    'manage_terms' => Constant::CAP_MANAGE_CATAGORIES,
    'edit_terms' => Constant::CAP_MANAGE_CATAGORIES,
    'delete_terms' => Constant::CAP_MANAGE_CATAGORIES,
    'assign_terms' => Constant::CAP_MANAGE_CATAGORIES,
];

return [
    'hierarchical' => false,
    'label' => __('Books', DOMAIN),
    'labels' => [
        'name' => __('Bible books', DOMAIN),
        'singular_name' => __('Book', DOMAIN),
        'menu_name' => _x('Books', 'menu', DOMAIN),
        'search_items' => __('Search books', DOMAIN),
        'all_items' => __('All books', DOMAIN),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit book', DOMAIN),
        'update_item' => __('Update book', DOMAIN),
        'add_new_item' => __('Add new book', DOMAIN),
        'new_item_name' => __('New book name', DOMAIN),
        'not_found' => __('No books found', DOMAIN),
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
