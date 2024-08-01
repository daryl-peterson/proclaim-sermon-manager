<?php

namespace DRPSermonManager\Admin;

use DRPSermonManager\App;
use DRPSermonManager\Constant;
use DRPSermonManager\Interfaces\Initable;
use DRPSermonManager\Interfaces\Registrable;
use DRPSermonManager\Logging\Logger;
use DRPSermonManager\MetaInput;
use DRPSermonManager\PostMeta\BiblePassage;
use DRPSermonManager\PostMeta\SermonAudio;
use DRPSermonManager\PostMeta\SermonDate;
use DRPSermonManager\PostMeta\SermonDuration;
use DRPSermonManager\PostMeta\SermonNotes;
use DRPSermonManager\PostMeta\ServiceType;
use DRPSermonManager\PostMeta\VideoEmbed;
use DRPSermonManager\PostMeta\VideoLink;
use DRPSermonManager\PostType\Sermon;
use DRPSermonManager\TaxUtils;

use const DRPSermonManager\DOMAIN;

defined('ABSPATH') or exit;

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
        add_filter('use_block_editor_for_post_type', [$this, 'disableGutenberg'], 10, 2);
        add_action('admin_menu', [$this, 'setMetaBoxes']);

        add_action('save_post_drpsermon', [$this, 'savePost'], 40, 3);
        // 'save_post_wpfc_sermon'
        // add_action("edit_post","update_multiple_sermon_meta_data");
    }

    public function savePost(int $post_id, \WP_Post $post, bool $update): int
    {
        try {
            if (!Sermon::isSavable($post_id, $post)) {
                return $post_id;
            }

            SermonDate::init()->set($post_id);
            BiblePassage::init()->set($post_id);
            SermonAudio::init()->set($post_id);
            SermonDuration::init()->set($post_id);
            SermonNotes::init()->set($post_id);
            ServiceType::init()->set($post_id);
            VideoEmbed::init()->set($post_id);
            VideoLink::init()->set($post_id);

            return $post_id;
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
            // @codeCoverageIgnoreEnd
        }

        return $post_id;
    }

    public function fixDate($value)
    {
        Logger::debug(['VALUE' => $value]);

        return $value;
    }

    public function disableGutenberg(bool $current_status, string $post_type): bool
    {
        if ($post_type === Constant::POST_TYPE_SERMON) {
            return false;
        }

        return (bool) $current_status;
    }

    public function setMetaBoxes(): void
    {
        $this->removeMetaBoxes();

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
        wp_nonce_field('sermon-edit', 'sermon-edit-'.$post->ID);
        $html = '';
        $html .= MetaInput::getText(SermonDate::init(), $post->ID);
        $html .= MetaInput::getSelect(ServiceType::init(), $post->ID);
        $html .= MetaInput::getText(SermonDuration::init(), $post->ID);
        $html .= MetaInput::getText(BiblePassage::init(), $post->ID);

        echo $html;
    }

    public function addSermonFiles(\WP_Post $post): void
    {
        $html = MetaInput::getText(SermonAudio::init(), $post->ID);
        $html .= MetaInput::getTextArea(VideoEmbed::init(), $post->ID);
        $html .= MetaInput::getText(VideoLink::init(), $post->ID);
        $html .= MetaInput::getFileInput(SermonNotes::init(), $post->ID);

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

    private function removeMetaBoxes()
    {
        // @codeCoverageIgnoreStart
        if (!function_exists('\remove_meta_box')) {
            $file = ABSPATH.'wp-admin/includes/template.php';
            Logger::debug("Including file: $file");
            require_once $file;
        }
        // @codeCoverageIgnoreEnd

        remove_meta_box('postcustom', $this->postType, 'normal');
        remove_meta_box('tagsdiv-'.Constant::TAX_SERVICE_TYPE, $this->postType, 'normal');
    }
}
