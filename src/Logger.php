<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Interfaces\LoggerInterface;
use DRPSermonManager\Core\Traits\SingletonTrait;
use DRPSermonManager\Interfaces\LogFormatterInterface;

/**
 * Logger class.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class Logger implements LoggerInterface
{
    use SingletonTrait;

    private LogFormatterInterface $formatter;

    protected function __construct()
    {
        // @codeCoverageIgnoreStart
        $this->formatter = App::getLogFormatterInt();
        // @codeCoverageIgnoreEnd
    }

    public static function debug(mixed $context): bool
    {
        return self::log($context, 'debug');
    }

    public static function error(mixed $context): bool
    {
        return self::log($context, 'error');
    }

    public static function info(mixed $context): bool
    {
        return self::log($context, 'info');
    }

    private static function log(mixed $context, string $level): bool
    {
        try {
            $record = new LogRecord(__FILE__, $context, $level, debug_backtrace(0, 8));
            $obj = Logger::getInstance();
            $data = $obj->formatter->format($record);

            $file = LogFile::get($level);
            if ($level === 'error') {
                error_log($data);
            }

            return file_put_contents($file, $data, FILE_APPEND);
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            return false;
            // @codeCoverageIgnoreEnd
        }
    }
}
