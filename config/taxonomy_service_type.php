<?php

namespace DRPSermonManager;

use DRPSermonManager\Constants\CAP;
use DRPSermonManager\Constants\TAX;

$permalinks = App::getPermalinkStructureInt()->get();
$opts = App::getOptionsInt();
$tax = TAX::SERVICE_TYPE;
$capabilities = [
    'manage_terms' => CAP::MANAGE_CATAGORIES,
    'edit_terms' => CAP::MANAGE_CATAGORIES,
    'delete_terms' => CAP::MANAGE_CATAGORIES,
    'assign_terms' => CAP::MANAGE_CATAGORIES,
];
$label = __('Service Type', DOMAIN);
$optLabel = strtolower($opts->get('service_type_label', ''));
if (!empty($optLabel)) {
    $label = $optLabel;
}

return [
    'hierarchical' => false,
    'label' => ucwords($label),
    'labels' => [
        'name' => ucwords($label.'s'),
        'singular_name' => ucwords($label),
        'menu_name' => ucwords($label.'s'),
        'search_items' => wp_sprintf(__('Search %s', DOMAIN), $label),
        'all_items' => wp_sprintf(__('All %s', DOMAIN), $label),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => wp_sprintf(__('Edit %s', DOMAIN), $label),
        'update_item' => wp_sprintf(__('Update %s', DOMAIN), $label),
        'add_new_item' => wp_sprintf(__('Add new %s', DOMAIN), $label),
        'new_item_name' => wp_sprintf(__('New %s name', DOMAIN), $label),
        'not_found' => wp_sprintf(__('No %s found', DOMAIN), $label),
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
