<?php

namespace DRPSermonManager;

/**
 * Fix Log size.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class LogFile
{
    public static function get(string $level): ?string
    {
        $obj = new static();
        $log = $obj->getLogFile($level);
        if (isset($log)) {
            $obj->checkFileSize($log);
        }

        return $log;
    }

    public function checkFileSize(string $file): void
    {
        try {
            $fs = filesize($file);

            if (!$fs || ($fs < 2000000)) {
                return;
            }
            // @codeCoverageIgnoreStart
            $this->truncate($file);
        } catch (\Throwable $th) {
            // @codeCoverageIgnoreEnd
        }
    }

    public function getLogFile(string $level): ?string
    {
        // @codeCoverageIgnoreStart

        $logFile = LOG_FILE;
        if (defined('WP_DEBUG_LOG')) {
            $logFile = dirname(WP_DEBUG_LOG).'/'.LOG_FILE;
        }

        // @codeCoverageIgnoreEnd
        return $logFile;
    }

    public function truncate(string $file): void
    {
        // @codeCoverageIgnoreStart
        try {
            $fh = fopen($file, 'w');
            if ($fh) {
                fclose($fh);
            }
        } catch (\Throwable $th) {
        }
        // @codeCoverageIgnoreEnd
    }
}
