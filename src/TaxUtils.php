<?php

namespace DRPSermonManager;

use DRPSermonManager\Constants\PT;

/**
 * Taxonomy utilities.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class TaxUtils
{
    /**
     * Return registered taxonomies for post type.
     *
     * @return array Array of taxonomy names
     *
     * @since 1.0.0
     */
    public static function getTaxonomies(string $post_type = ''): array
    {
        if (empty($post_type)) {
            $post_type = PT::SERMON;
        }

        return get_object_taxonomies($post_type);
    }

    /**
     * Get taxonomy field.
     */
    public static function getTaxonomyField(string|int|\WP_Taxonomy $taxonomy, string $field_name): ?string
    {
        $taxonomy = get_taxonomy($taxonomy);

        if (!$taxonomy instanceof \WP_Taxonomy) {
            return null;
        }

        if (isset($taxonomy->$field_name)) {
            return $taxonomy->$field_name;
        }

        if (isset($taxonomy->labels->$field_name)) {
            return $taxonomy->labels->$field_name;
        }

        return null;
    }
}
