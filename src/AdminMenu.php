<?php

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\AdminMenuInt;

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
class AdminMenu implements AdminMenuInt
{
    public static function init(): AdminMenuInt
    {
        return new self();
    }

    public function register(): void
    {
        add_action('admin_enqueue_scripts', [$this, 'fixIcon']);
    }

    public function fixIcon()
    {
        wp_enqueue_style('drp-admin-icon', Helper::getUrl().'assets/css/admin-icon.css', []);
    }
}
