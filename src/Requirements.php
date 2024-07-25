<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Interfaces\NoticeInterface;
use DRPSermonManager\Core\Interfaces\RequirementsInterface;

/**
 * Register requirement checks to be run.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 */
class Requirements implements RequirementsInterface
{
    private NoticeInterface $notice;

    public function __construct(NoticeInterface $notice)
    {
        $this->notice = $notice;
    }

    public function init(): void
    {
        try {
            $hook = Helper::getKeyName('REQUIREMENTS_INIT');

            if (did_action($hook) && !defined('PHPUNIT_TESTING')) {
                // @codeCoverageIgnoreStart
                return;
                // @codeCoverageIgnoreEnd
            }
            add_action('admin_init', [$this, 'isCompatible']);
            Logger::debug('REQUIREMENTS HOOKS INITIALIZED');
            do_action($hook);

            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
            // @codeCoverageIgnoreEnd
        }
    }

    public function notice(): NoticeInterface
    {
        return $this->notice;
    }

    public function isCompatible(): void
    {
        $transient = Helper::getKeyName('compatible');
        try {
            Logger::debug('CHECKING REQUIREMENTS');
            $obj = new RequirementChecks();
            $obj->run();
            Logger::debug('REQUIREMENTS MET');

            set_transient($transient, true, 500);
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            delete_transient($transient);
            $this->deactivate();

            return;
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Deactivate plugin.
     */
    private function deactivate(): void
    {
        if (is_admin() && current_user_can('activate_plugins')) {
            deactivate_plugins(FILE);
            // @codeCoverageIgnoreStart
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
            // @codeCoverageIgnoreEnd
        }
    }
}
