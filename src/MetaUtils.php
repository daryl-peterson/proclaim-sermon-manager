<?php

namespace DRPSermonManager;

/**
 * Meta utilities.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class MetaUtils
{
    public static function getAllPostMeta(int $post_id): array
    {
        $meta = get_post_meta($post_id, '');

        if (!is_array($meta)) {
            return [];
        }

        // We only need the first item - return a flat array.
        $meta = array_map(function ($item) {
            return $item[0];
        }, $meta);

        Logger::debug(['META' => $meta]);

        return $meta;
    }

    public static function getRawMeta(int $post_id, string $meta_key): ?object
    {
        global $wpdb;
        $mid = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s", $post_id, $meta_key));

        Logger::debug(['RAW META' => $mid]);
        if (!is_array($mid) || !isset($mid[0])) {
            return null;
        }

        // @codeCoverageIgnoreStart
        return $mid[0];
        // @codeCoverageIgnoreEnd
    }
}
