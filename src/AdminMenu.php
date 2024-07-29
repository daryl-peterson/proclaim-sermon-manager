<?php

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\AdminMenuInt;
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
        $file = Helper::getUrl().'assets/css/admin-icon.css';
        Logger::debug(['CSS FILE' => $file]);
        wp_enqueue_style('drp-admin-icon', $file, []);
    }
}
