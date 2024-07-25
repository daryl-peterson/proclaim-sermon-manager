<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Exceptions\PluginException;
use DRPSermonManager\Core\Interfaces\NoticeInterface;

/**
 * Run checks to see if requirements are met. If not throw PluginException.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 */
class RequirementChecks
{
    private NoticeInterface $notice;

    public function __construct()
    {
        $this->notice = App::getNoticeInt();
    }

    public function run()
    {
        $this->checkPHPVer();
        $this->checkWPVer();
    }

    public function checkPHPVer(string $version = ''): void
    {
        if (empty($version)) {
            $version = PLUGIN_MIN_PHP;
        }
        $message = 'This Plugin requires PHP: '.$version;
        if (version_compare(PHP_VERSION, $version) >= 0) {
            return;
        }
        $this->notice->setError('- Requirement Not Met', $message);
        throw new PluginException($message);
    }

    public function checkWPVer(string $version = ''): void
    {
        global $wp_version;

        if (empty($version)) {
            $version = PLUGIN_MIN_WP;
        }
        $message = 'This Plugin requires WP : '.$version;
        if (version_compare($wp_version, $version) >= 0) {
            return;
        }
        $this->notice->setError('- Requirement Not Met', $message);
        throw new PluginException($message);
    }
}
