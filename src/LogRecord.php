<?php

namespace DRPSermonManager;

/**
 * Get log record object.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 */
class LogRecord
{
    public string $date;
    public string $level;
    public string $class;
    public string $function;
    public string $line;
    public string $file;
    public string $context;

    public function __construct(string $file, mixed $context, string $level, array $trace)
    {
        $datetime = new \DateTime('now', wp_timezone());
        $dt = $datetime->format('m-d-Y H:i:s.u e');
        $this->date = $dt;
        $this->level = strtoupper($level);
        $this->class = $this->function = $this->line = $this->file = '';
        $this->context = $this->fixContext($context);

        $start = $this->getStartPos($trace);

        if (isset($start)) {
            $this->getTraceInfo($trace, $start);
        }

        $dir = Helper::getPluginDir();
        $this->file = str_replace($dir, '', $this->file);
    }

    private function getStartPos(array $trace): ?int
    {
        try {
            $base = 0;

            foreach ($trace as $key => $value) {
                // $file = $value['file'];

                if (isset($value['function']) && ($value['function'] === 'log')) {
                    $base = $key + 1;
                    break;
                }
            }

            return $base;
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            return null;
            // @codeCoverageIgnoreEnd
        }
    }

    private function getTraceInfo(array $trace, $base): void
    {
        $next = $base + 1;

        $info = [
            'class' => $next,
            'function' => $next,
            'line' => $base,
            'file' => $base,
        ];

        foreach ($info as $name => $pos) {
            if (isset($trace[$pos][$name])) {
                $this->$name = $trace[$pos][$name];
            }
        }

        $this->file .= "\n";
    }

    private function fixContext(mixed $context): string
    {
        $result = '';
        if (is_wp_error($context)) {
            /* @var \WP_Error */

            $result .= 'WP ERROR : '.$context->get_error_message()."\n";
        } elseif (is_array($context) || is_object($context)) {
            $result = print_r($context, true);
        } elseif (is_string($context)) {
            $result .= $context;
        }

        $result .= "\n";

        return $result;
    }
}
