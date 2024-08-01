<?php

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\PostMetaInt;
use DRPSermonManager\Logging\Logger;

defined('ABSPATH') or exit;
/**
 * Metabox helper class.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class MetaInput
{
    public static function getFileInput(PostMetaInt $meta, int $post_id)
    {
        $value = $meta->get($post_id);
        $label = $meta->getLabel();
        $field = $meta->getName();
        $button = $field.'_button';
        $description = self::getInputDescription($meta);
        $attachments = $meta->getAttachments($post_id);
        $data = '';
        $ids = [];

        if (count($attachments) > 0) {
            $ul = $field.'_ul';
            $data .= <<<EOT
            <ul id="$ul" name="$ul" class="ui-sortable">
            EOT;
        }

        /*
         * @var Attachment
         */
        foreach ($attachments as $attachment) {
            $id = $attachment->id;
            $li = $field.'_li_'.$id;
            $field_id = $field.'_list_'.$id;
            $field_name = $field."_list[$id]";

            $ids[] = $attachment->id;
            $data .= <<<EOT
                <input id="$field_id" name="$field_name" type="hidden" value="$id">
                <li id="$li" name="$li" class="file-status ui-sortable-handle">
                    <span>File: <strong>$attachment->file</strong></span>
                    <a href="$attachment->url" target="_blank" rel="external">Download</a> /
                    <a href="#" data-id="$id" data-field="$field_id" data-li="$li" class="file-remove">Remove</a>
                </li>
            EOT;
        }

        if (strlen($data) > 0) {
            $data .= '</ul>';
        }

        $html = <<<EOT
            <div class="row-wrap">
                <div class="col-label">
                    <label for="$field">$label</label>
                </div>
                <div class="col-field">
                    <input id="$field" name="$field" class="regular-text" type="text" value="$value" autocomplete="off">
                    $description
                    <input id="$button" name="$button" class="button" type="button" value="Add File">
                    $data
                </div>
            </div>
        EOT;

        return $html;
    }

    public static function getTextArea(PostMetaInt $meta, int $post_id): string
    {
        $value = $meta->get($post_id);
        $label = $meta->getLabel();
        $field = $meta->getName();
        $description = self::getInputDescription($meta);

        $html = <<<EOT
            <div class="row-wrap">
                <div class="col-label">
                    <label for="$field">$label</label>
                </div>
                <div class="col-field">
                    <textarea name="$field" id="$field" cols="60" rows="10">$value</textarea>
                    $description



                </div>
            </div>
        EOT;

        return $html;
    }

    public static function getText(PostMetaInt $meta, int $post_id)
    {
        $value = $meta->get($post_id);
        $label = $meta->getLabel();
        $field = $meta->getName();
        $description = self::getInputDescription($meta);

        $html = <<<EOT
            <div class="row-wrap">
                <div class="col-label">
                    <label for="$field">$label</label>
                </div>
                <div class="col-field">
                    <input type="text" name="$field" id="$field" value="$value" autocomplete="off">
                    $description
                </div>
            </div>
        EOT;

        return $html;
    }

    public static function getSelect(PostMetaInt $meta, int $post_id): string
    {
        $value = $meta->get($post_id);
        $label = $meta->getLabel();
        $field = $meta->getName();
        $terms = $meta->getTerms();
        $description = self::getInputDescription($meta);

        $options = '';
        $options .= "<option value=''>None</option>";
        foreach ($terms as $term) {
            $selected = '';
            if ((int) $term->term_id === (int) $value) {
                $selected = ' selected';
            }
            // Logger::debug(['TERM ID' => $term->term_id, 'VALUE' => $value, 'SELECTED' => $selected]);
            $options .= <<<EOT
            <option value="$term->term_id"$selected>$term->name</option>
            EOT;
        }

        $html = <<<EOT
            <div class="row-wrap">
                <div class="col-label">
                    <label for="$field">$label</label>
                </div>
                <div class="col-field">
                    <select name="$field" id="$field">
                    $options
                    </select>
                    $description
                </div>
            </div>
        EOT;

        return $html;
    }

    private static function getInputDescription(PostMetaInt $meta): string
    {
        $description = $meta->getDescription();
        $html = '';
        if (isset($description)) {
            $html = <<<EOT
                <span class="input-description">$description</span>
            EOT;
        }

        return $html;
    }
}
