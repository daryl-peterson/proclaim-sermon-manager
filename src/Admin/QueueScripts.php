<?php

namespace DRPSermonManager\Admin;

use DRPSermonManager\Helper;
use DRPSermonManager\Interfaces\Initable;
use DRPSermonManager\Interfaces\Registrable;
use DRPSermonManager\Logging\Logger;

/**
 * Admin menu.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class QueueScripts implements Initable, Registrable
{
    public static function init(): QueueScripts
    {
        return new self();
    }

    public function register(): void
    {
        add_action('admin_init', [$this, 'initScriptStyles']);
        add_action('admin_enqueue_scripts', [$this, 'load']);
        add_action('admin_footer', [$this, 'footer']);
    }

    public function initScriptStyles()
    {
        // @codeCoverageIgnoreStart
        $file = Helper::getUrl().'assets/css/admin.css';
        Logger::debug(['CSS FILE' => $file]);
        wp_register_style('drpsermon-admin-style', $file);

        // $file = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css';
        // wp_register_style('drpsermon-jquery-ui-style', $file);

        $file = Helper::getUrl().'assets/js/admin.js';
        wp_register_script('drpsermon-admin-script', $file);
        // @codeCoverageIgnoreEnd
    }

    public function load(): void
    {
        if (is_admin()) {
            // @codeCoverageIgnoreStart
            wp_enqueue_style('drpsermon-admin-style');
            wp_enqueue_media();
            // @codeCoverageIgnoreEnd
        }
    }

    public function footer()
    {
        if (!is_admin()) {
            return;
        }
        // wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('drpsermon-admin-script');
    }
}
