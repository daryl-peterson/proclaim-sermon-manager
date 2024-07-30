<?php

namespace DRPSermonManager;

use DRPSermonManager\Logging\Logger;

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
class MetaBox
{
    public static function getTextArea(int $post_id, string $label, string $field)
    {
        $value = get_post_meta($post_id, $field, true);

        $value = apply_filters($field.'_filter_meta', $value);
        $html = <<<EOT
            <div class="admin-row">
                <div class="admin-label">
                    <label for="$field">$label</label>
                </div>
                <div class="admin-field">
                    <textarea name="$field" id="$field" class="wp-core-ui">$value</textarea>
                </div>
            </div>
        EOT;

        return $html;
    }

    public static function getText(int $post_id, string $label, string $field)
    {
        $value = get_post_meta($post_id, $field, true);

        $value = apply_filters($field.'_filter_meta', $value);
        $html = <<<EOT
            <div class="admin-row">
                <div class="admin-label">
                    <label for="$field">$label</label>
                </div>
                <div class="admin-field">
                    <input type="text" name="$field" id="$field" value="$value" autocomplete="off" class="wp-core-ui">
                </div>
            </div>
        EOT;

        return $html;
    }

    public static function getSelect(int $post_id, string $taxonomy, string $label, string $field): string
    {
        $term_id = get_post_meta($post_id, $field, true);
        $term_id = apply_filters($field.'_filter_meta', $term_id);
        $terms = get_terms(
            [
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
                'orderby' => 'name',
                'order' => 'ASC',
            ]
        );

        $options = '';
        $options .= "<option value=''>None</option>";
        foreach ($terms as $term) {
            $selected = '';
            if ((int) $term->term_id === (int) $term_id) {
                $selected = ' selected';
            }
            Logger::debug(['TERM ID' => $term->term_id, 'VALUE' => $term_id, 'SELECTED' => $selected]);
            $options .= <<<EOT
            <option value="$term->term_id"$selected>$term->name</option>
            EOT;
        }

        $html = <<<EOT
            <div class="admin-row">
                <div class="admin-label">
                    <label for="$field">$label</label>
                </div>
                <div class="admin-field">
                    <select name="$field" id="$field" class="wp-core-ui select">
                    $options
                    </select>
                </div>
            </div>
        EOT;

        return $html;
    }
}
