<?php

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\NoticeInterface;
use DRPSermonManager\Interfaces\PluginInterface;

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
            App::getAdminPage()->init();
            App::getTextDomainInt();

            Logger::debug('PLUGIN HOOKS INITIALIZED');
            do_action($hook);

            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
            // @codeCoverageIgnoreEnd
        }
    }

    public function activate(): void
    {
        Logger::debug('Activated');
        // @todo Add activation cleanup
    }

    public function deactivate(): void
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
