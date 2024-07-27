<?php

namespace DRPSermonManager;

use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Interfaces\NoticeInt;
use DRPSermonManager\Interfaces\RequirementsInt;

/**
 * Register requirement checks to be run.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 */
class Requirements implements RequirementsInt
{
    private NoticeInt $notice;
    private RequirementCheck $require;
    private bool $fail;

    protected function __construct()
    {
        $this->notice = App::getNoticeInt();
        $this->require = App::getRequirementCheckInt();
        $this->fail = false;
    }

    public static function init(): RequirementsInt
    {
        return new self();
    }

    public function register(): void
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

    public function notice(): NoticeInt
    {
        return $this->notice;
    }

    public function setFail(bool $fail): void
    {
        $this->fail = $fail;
    }

    public function isCompatible(): void
    {
        $transient = Helper::getKeyName('compatible');
        try {
            Logger::debug('CHECKING REQUIREMENTS');

            App::getRequirementCheckInt()->run();
            if ($this->fail) {
                throw new PluginException('Force fail');
            }
            Logger::debug('REQUIREMENTS MET');

            set_transient($transient, true, 500);
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            delete_transient($transient);
            $this->deactivate();

            return;
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Deactivate plugin.
     */
    private function deactivate(): void
    {
        if (is_admin() && current_user_can('activate_plugins')) {
            // @codeCoverageIgnoreStart
            deactivate_plugins(FILE);
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
            // @codeCoverageIgnoreEnd
        }
    }
}
