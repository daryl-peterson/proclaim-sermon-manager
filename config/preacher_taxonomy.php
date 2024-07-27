<?php

namespace DRPSermonManager;

// The labels with their defaults in the singular lowercase form.
$labels = [
    'wpfc_preacher' => SermonManager::getOption('preacher_label') ? strtolower(SermonManager::getOption('preacher_label')) : __('Preacher', DOMAIN),
    'wpfc_service_type' => SermonManager::getOption('service_type_label') ? strtolower(SermonManager::getOption('service_type_label')) : __('Service Type', DOMAIN),
];

return [
    'hierarchical' => false,
    'label' => ucwords($labels['wpfc_preacher']),
    'labels' => [
        'name' => ucwords($labels['wpfc_preacher'].'s'),
        'singular_name' => ucwords($labels['wpfc_preacher']),
        'menu_name' => ucwords($labels['wpfc_preacher'].'s'),
        /* translators: %s preacher */
        'search_items' => wp_sprintf(__('Search %s', DOMAIN), $labels['wpfc_preacher']),
        /* translators: %s preacher */
        'all_items' => wp_sprintf(__('All %s', DOMAIN), $labels['wpfc_preacher']),
        'parent_item' => null,
        'parent_item_colon' => null,
        /* translators: %s preacher */
        'edit_item' => wp_sprintf(__('Edit %s', DOMAIN), $labels['wpfc_preacher']),
        /* translators: %s preacher */
        'update_item' => wp_sprintf(__('Update %s', DOMAIN), $labels['wpfc_preacher']),
        /* translators: %s preacher */
        'add_new_item' => wp_sprintf(__('Add new %s', DOMAIN), $labels['wpfc_preacher']),
        /* translators: %s preacher */
        'new_item_name' => wp_sprintf(__('New %s name', DOMAIN), $labels['wpfc_preacher']),
        /* translators: %s preacher */
        'not_found' => wp_sprintf(__('No %s found', DOMAIN), $labels['wpfc_preacher']),
    ],
    'show_ui' => true,
    'query_var' => true,
    'show_in_rest' => true,
    'rewrite' => [
        'slug' => $permalinks['wpfc_preacher'],
        'with_front' => false,
    ],
    'capabilities' => $capabilities,
];
