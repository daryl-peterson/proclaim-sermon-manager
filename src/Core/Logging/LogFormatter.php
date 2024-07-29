<?php

namespace DRPSermonManager\Logging;

use DRPSermonManager\Interfaces\LogFormatterInt;

/**
 * Format log record.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 */
class LogFormatter implements LogFormatterInt
{
    public function format(LogRecord $record): string
    {
        $log = '';

        $log .= str_repeat('*', 80)."\n";
        foreach ($record as $key => $value) {
            $log .= $this->padStr($key).$value."\n";
        }

        return $log;
    }

    private function padStr($name)
    {
        return substr(strtoupper($name).str_pad(' ', 10), 0, 10).': ';
    }
}
