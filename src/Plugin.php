<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Interfaces\NoticeInterface;
use DRPSermonManager\Core\Interfaces\PluginInterface;

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
class Plugin implements PluginInterface
{
    private NoticeInterface $notice;

    public function __construct()
    {
        $this->notice = App::getNoticeInt();
    }

    public function init(): void
    {
        try {
            $hook = Helper::getKeyName('PLUGIN_INIT');

            if (did_action($hook) && !defined('PHPUNIT_TESTING')) {
                // @codeCoverageIgnoreStart
                return;
                // @codeCoverageIgnoreEnd
            }
            register_activation_hook(FILE, [$this, 'activate']);
            register_deactivation_hook(FILE, [$this, 'deactivate']);
            add_action('shutdown', [$this, 'shutdown']);
            add_action('admin_notices', [$this, 'showNotice']);

            App::getRequirementsInt()->init();
            App::getSermonPostInt()->init();
            App::getAdminPage()->init();
            Logger::debug('PLUGIN HOOKS INITIALIZED');
            do_action($hook);

            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error($th->getMessage());
            // @codeCoverageIgnoreEnd
        }
    }

    public function activate()
    {
        Logger::debug('Activated');
        // @todo Add activation cleanup
    }

    public function deactivate()
    {
        Logger::debug('DEACTIVATING');
        // @todo Add deactivation cleanup
    }

    public function showNotice(): void
    {
        $this->notice->showNotice();
    }

    public function shutdown(): void
    {
        Logger::debug("SHUTDOWN\n".str_repeat('-', 80)."\n\n\n");
    }
}
