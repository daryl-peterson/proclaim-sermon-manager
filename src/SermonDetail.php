<?php

namespace DRPSermonManager;

use DRPSermonManager\Constants\META;
use DRPSermonManager\Constants\PT;
use DRPSermonManager\Constants\TAX;

defined('ABSPATH') or exit;

/**
 * Show sermon deail meta box.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class SermonDetail
{
    public static function init()
    {
        return new self();
    }

    public function show(): void
    {
        $options = Options::init();

        switch ($options->get('date_format', '')) {
            case '0':
                $date_format_label = 'mm/dd/YYYY';
                $date_format = 'm/d/Y';
                break;
            case '1':
                $date_format_label = 'dd/mm/YYYY';
                $date_format = 'd/m/Y';
                break;
            case '2':
                $date_format_label = 'YYYY/mm/dd';
                $date_format = 'Y/m/d';
                break;
            case '3':
                $date_format_label = 'YYYY/dd/mm';
                $date_format = 'Y/d/m';
                break;
            default:
                $date_format_label = 'mm/dd/YYYY';
                $date_format = 'm/d/Y';
                break;
        }

        $post_type = PT::SERMON;

        /**
         * Initiate the metabox.
         */
        $cmb = new_cmb2_box([
            'id' => 'sermon_details',
            'title' => __('Sermon Details', DOMAIN),
            'object_types' => [$post_type], // Post type
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true,           // Show field names on the left
            // 'cmb_styles' => false,       // false to disable the CMB stylesheet
            // 'closed'     => true,        // Keep the metabox closed by default
        ]);

        // Date Preached
        $cmb->add_field([
            'name' => esc_html__('Date Preached', DOMAIN),
            'desc' => '<br>'.wp_sprintf(esc_html__('format: %s', DOMAIN), $date_format_label),
            'id' => META::DATE,
            'type' => 'text_date_timestamp',
            'date_format' => $date_format,
            'autocomplete' => 'off',
        ]);

        // Service Type
        $tax = TAX::SERVICE_TYPE;
        $meta = META::SERVICE_TYPE;
        $field = strtolower(TaxUtils::getTaxonomyField($post_type, 'singular_name').'');
        $label = strtolower(TaxUtils::getTaxonomyField($tax, 'label').'');
        $link = '<a href="'.admin_url("edit-tags.php?taxonomy=$tax&post_type=$post_type").' target="_blank">here</a>';
        $desc = wp_sprintf(
            esc_html__('Select the %1$s. Modify the %2$s %3$s.', DOMAIN),
            $field,
            $label,
            $link
        );

        // Main Bible Passage
        $meta = META::BIBLE_PASSAGE;
        $desc = wp_sprintf(
            esc_html__('Enter the Bible passage with the full book names, e.g. %1$s Or multiple books like %2$s', DOMAIN),
            '<code>'.esc_html__('John 3:16-18', DOMAIN).'</code><br>',
            '<code>'.
            esc_html__('John 3:16-18, Luke 2:1-3', DOMAIN).
            '</code>'
        );
        $cmb->add_field([
            'name' => esc_html__('Main Bible Passage', DOMAIN),
            'desc' => $desc,
            'id' => 'bible_passage',
            'type' => 'text',
        ]);

        // Email text field
        $meta = META::DESCRIPTION;
        $cmb->add_field([
            'name' => esc_html__('Description', DOMAIN),
            'desc' => esc_html__('Type a brief description about this sermon, an outline, or a full manuscript',
                DOMAIN),
            'id' => $meta,
            'type' => 'wysiwyg',
            'options' => [
                'textarea_rows' => 7,
                'media_buttons' => true,
            ],
        ]);

        // Add other metaboxes as needed
    }
}
