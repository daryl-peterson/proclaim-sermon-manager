<?php

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\DeactivatorInt;

/**
 * Deactivate plugin.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class Deactivator implements DeactivatorInt
{
    public static function init(): DeactivatorInt
    {
        return new self();
    }

    public function run(): void
    {
        if ((is_admin() && current_user_can('activate_plugins')) || defined('PHPUNIT_TESTING')) {
            deactivate_plugins(FILE);
            if (isset($_GET['activate'])) {
                // @codeCoverageIgnoreStart
                unset($_GET['activate']);
                // @codeCoverageIgnoreEnd
            }
        }
    }
}
