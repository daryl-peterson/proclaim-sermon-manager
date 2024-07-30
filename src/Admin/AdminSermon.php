<?php

namespace DRPSermonManager\Admin;

use DRPSermonManager\App;
use DRPSermonManager\Constant;
use DRPSermonManager\Interfaces\Initable;
use DRPSermonManager\Interfaces\Registrable;
use DRPSermonManager\Logging\Logger;
use DRPSermonManager\MetaBox;
use DRPSermonManager\TaxUtils;

use const DRPSermonManager\DOMAIN;

/**
 * Admin sermon post edit / add.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class AdminSermon implements Initable, Registrable
{
    protected string $postType;

    protected function __construct()
    {
        $this->postType = Constant::POST_TYPE_SERMON;
    }

    public static function init(): AdminSermon
    {
        return new self();
    }

    public function register(): void
    {
        add_action('pre_get_posts', [$this, 'fixOrdering'], 90);
        // add_filter('replace_editor', [$this, 'modify_replace_editor_defaults'], 10, 2);
        // add_action('add_meta_boxes', [$this, 'extra_info_add_meta_boxes']);
        add_filter('use_block_editor_for_post_type', [$this, 'disableGutenberg'], 10, 2);
        add_action('admin_menu', [$this, 'setMetaBoxes']);
        add_filter(Constant::META_DATE.'_filter_meta', [$this, 'fixDate']);

        // 'save_post_wpfc_sermon'

        // add_action("edit_post","update_multiple_sermon_meta_data");

        add_action('save_post_drpsermon', [$this, 'savePost'], 40, 3);
    }

    public function savePost($post_id, $post, $update): mixed
    {
        // nonce check
        /*
        if (!isset($_POST['_mishanonce']) || !wp_verify_nonce($_POST['_mishanonce'], 'somerandomstr')) {
            return $post_id;
        }
        */

        if (Constant::POST_TYPE_SERMON !== $post->post_type) {
            return $post_id;
        }

        // check current user permissions
        $post_type = get_post_type_object($post->post_type);

        if (!current_user_can($post_type->cap->edit_post, $post_id)) {
            return $post_id;
        }

        // Do not save the data if autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        $service = Constant::META_SERVICE_TYPE;
        Logger::debug(['POST' => $_POST]);
        if (isset($_POST[$service])) {
            Logger::debug(['UPDATE SERVICE TYPE' => $_POST[$service]]);
            update_post_meta($post_id, $service, sanitize_text_field($_POST[$service]));
        } else {
            delete_post_meta($post_id, $service);
        }

        $field = Constant::META_BIBLE_PASSAGE;
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        } else {
            delete_post_meta($post_id, $field);
        }

        return $post_id;
    }

    public function fixDate($value)
    {
        Logger::debug(['VALUE' => $value]);

        return $value;
    }

    public function disableGutenberg($current_status, $post_type)
    {
        // Use your post type key instead of 'product'
        if ($post_type === Constant::POST_TYPE_SERMON) {
            return false;
        }

        return $current_status;
    }

    public function setMetaBoxes()
    {
        remove_meta_box('postcustom', $this->postType, 'normal');
        remove_meta_box('tagsdiv-'.Constant::TAX_SERVICE_TYPE, $this->postType, 'normal');
        remove_meta_box('astra_settings_meta_box', $this->postType, 'normal');

        add_meta_box(
            'sermon_details',               // Metabox ID
            __('Sermon Details', DOMAIN),   // Title
            [$this, 'addSermonDetails'],    // Callback function
            $this->postType,                // Add meta box to custom post type (or post types in array)
            'normal',                       // Position (normal, side, advanced)
            'core'                          // Priority (default, low, high, core)
        );

        add_meta_box(
            'sermon_files',                 // Metabox ID
            __('Sermon Files', DOMAIN),     // Title
            [$this, 'addSermonFiles'],    // Callback function
            $this->postType,                // Add meta box to custom post type (or post types in array)
            'normal',                       // Position (normal, side, advanced)
            'core'                          // Priority (default, low, high, core)
        );
    }

    public function addSermonDetails(\WP_Post $post): void
    {
        $html = MetaBox::getText($post->ID, 'Date Preached', Constant::META_DATE);
        $html .= MetaBox::getSelect($post->ID, Constant::TAX_SERVICE_TYPE, 'Service Type', Constant::META_SERVICE_TYPE);
        $html .= MetaBox::getText($post->ID, 'Bible Passage', Constant::META_BIBLE_PASSAGE);
        echo $html;
    }

    public function addSermonFiles(\WP_Post $post): void
    {
        $html = MetaBox::getText($post->ID, 'Sermon Audio', Constant::META_AUDIO);
        $html .= MetaBox::getText($post->ID, 'MP3 Duration', Constant::META_SERMON_DURATION);
        $html .= MetaBox::getTextArea($post->ID, 'Video Embed Code', Constant::META_VIDEO);
        $html .= MetaBox::getText($post->ID, 'Video Link', Constant::META_VIDEO_LINK);

        echo $html;
    }

    public function fixOrdering(\WP_Query $query): void
    {
        $pt = $this->postType;
        if (is_admin() || !$query->is_main_query()) {
            return;
        }

        $taxonomies = TaxUtils::getTaxonomies($pt);
        if (!is_post_type_archive($pt) && !is_tax($taxonomies)) {
            return;
        }

        $opts = App::getOptionsInt();
        $orderby = $opts->get('archive_orderby', '');
        $order = $opts->get('archive_order', '');

        switch ($orderby) {
            case 'date_preached':
                $query->set('meta_key', 'sermon_date');
                $query->set('meta_value_num', time());
                $query->set('meta_compare', '<=');
                $query->set('orderby', 'meta_value_num');
                break;
            case 'date_published':
                $query->set('orderby', 'date');
                break;
            case 'title':
            case 'random':
            case 'id':
                $query->set('orderby', $orderby);
                break;
        }

        $query->set('order', strtoupper($order));

        $query->set('posts_per_page', $opts->get('sermon_count', get_option('posts_per_page')));
    }
}
