<?php

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\Initable;
use DRPSermonManager\Interfaces\Runable;
use DRPSermonManager\Logging\Logger;

/**
 * Activate plugin.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class Activator implements Initable, Runable
{
    public static function init(): Activator
    {
        return new self();
    }

    public function run(): void
    {
        try {
            // @codeCoverageIgnoreStart
            if (!function_exists('\is_plugin_active')) {
                $file = ABSPATH.'wp-admin/includes/plugin.php';
                Logger::debug("Including file: $file");
                require_once $file;
            }
            // @codeCoverageIgnoreEnd

            if ((is_admin() && current_user_can('activate_plugins')) || defined('PHPUNIT_TESTING')) {
                activate_plugin(plugin_basename(FILE));
                if (isset($_GET['activate'])) {
                    // @codeCoverageIgnoreStart
                    unset($_GET['activate']);
                    // @codeCoverageIgnoreEnd
                }
            }
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
            // @codeCoverageIgnoreEnd
        }
    }
}
