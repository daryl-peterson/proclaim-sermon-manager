<?php

namespace DRPSermonManager;

use DRPSermonManager\Constants\META;
use DRPSermonManager\Constants\PT;
use DRPSermonManager\Logging\Logger;

defined('ABSPATH') or exit;

/**
 * Sermon files meta boxes.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class SermonFiles
{
    public static function init()
    {
        return new self();
    }

    public function show()
    {
        $post_type = PT::SERMON;

        $cmb = new_cmb2_box([
            'id' => $post_type,
            'title' => esc_html__('Sermon Files', DOMAIN),
            'object_types' => [$post_type],
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true,
        ]);
        $cmb->add_field([
            'name' => esc_html__('Location of MP3', DOMAIN),
            'desc' => esc_html__('Upload an audio file or enter an URL.', DOMAIN),
            'id' => META::AUDIO,
            'type' => 'file',
            'text' => [
                'add_upload_file_text' => 'Add Sermon Audio', // Change upload button text. Default: "Add or Upload File".
            ],
        ]);
        $cmb->add_field([
            'name' => esc_html__('MP3 Duration', DOMAIN),
            // translators: %s see msgid "hh:mm:ss", effectively <code>hh:mm:ss</code>.
            'desc' => wp_sprintf(
                esc_html__('Length in %s format (fill out only for remote files, local files will get data calculated by default)', DOMAIN),
                '<code>'.esc_html__('hh:mm:ss', DOMAIN).'</code>'
            ),
            'id' => META::DURATION,
            'type' => 'text',
        ]);
        $cmb->add_field([
            'name' => esc_html__('Video Embed Code', DOMAIN),
            'desc' => esc_html__('Paste your embed code for Vimeo, Youtube, Facebook, or direct video file here', DOMAIN),
            'id' => META::VIDEO,
            'type' => 'textarea_code',
        ]);
        $cmb->add_field([
            'name' => esc_html__('Video Link', DOMAIN),
            'desc' => esc_html__('Paste your link for Vimeo, Youtube, Facebook, or direct video file here', DOMAIN),
            'id' => META::VIDEO_LINK,
            'type' => 'text_url',
        ]);

        $cmb->add_field([
            'name' => esc_html__('Sermon Notes', DOMAIN),
            'desc' => esc_html__('Upload  pdf files.', DOMAIN),
            'id' => META::NOTES,
            'type' => 'file_list',
            'text' => [
                'add_upload_file_text' => esc_html__('Add File', DOMAIN),
                // Change upload button text. Default: "Add or Upload File".
            ],
            'query_args' => [
                'type' => 'application/pdf', // Make library only display PDFs.
                // Or only allow gif, jpg, or png images
                // 'type' => array(
                // 	'image/gif',
                // 	'image/jpeg',
                // 	'image/png',
                // ),
            ],
            'sanitization_cb' => [$this, 'santizePDF'],
        ]);
        $cmb->add_field([
            'name' => esc_html__('Bulletin', DOMAIN),
            'desc' => esc_html__('Upload pdf files.', DOMAIN),
            'id' => META::BULLETIN,
            'type' => 'file_list',
            'text' => [
                'add_upload_file_text' => esc_html__('Add File', DOMAIN),
                // Change upload button text. Default: "Add or Upload File".
            ],
        ]);
    }

    public function santizePDF($value, $field_args, $field)
    {
        Logger::debug(['VALUE' => $value, 'FIELD ARGS' => $field_args, 'FIELD' => $field]);

        return $value;
    }
}
