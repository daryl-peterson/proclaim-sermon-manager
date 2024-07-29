<?php

namespace DRPSermonManager\Abstracts;

use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Helper;
use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Logger;

/**
 * Post type registration abstract.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
abstract class PostTypeRegAbs implements PostTypeRegInt
{
    /**
     * Post type.
     */
    protected string $pt;

    /**
     * Congifurage file to read.
     */
    protected string $configFile;

    public function add(): void
    {
        $exist = $this->exist();
        if (!defined('PHPUNIT_TESTING')) {
            // @codeCoverageIgnoreStart
            if (!is_blog_installed() || $exist) {
                return;
            }
            // @codeCoverageIgnoreEnd
        }

        try {
            $def = Helper::getConfig($this->configFile);
            $result = register_post_type($this->pt, $def);
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
            // @codeCoverageIgnoreEnd
        }

        if (!$this->exist() || is_wp_error($result)) {
            // @codeCoverageIgnoreStart
            $message = 'Failed to add post type '.$this->pt;
            if (is_wp_error($result)) {
                $message = $this->getWpErrorMessage($result);
            }
            throw new PluginException($message);
            // @codeCoverageIgnoreEnd
        }
    }

    public function remove(): void
    {
        $exist = $this->exist();
        if (!defined('PHPUNIT_TESTING')) {
            // @codeCoverageIgnoreStart
            if (!is_blog_installed() || (!$exist)) {
                return;
            }
            // @codeCoverageIgnoreEnd
        }

        try {
            $result = unregister_post_type($this->pt);
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            // @codeCoverageIgnoreEnd
        }

        if ($this->exist() || is_wp_error($result)) {
            // @codeCoverageIgnoreStart
            $message = 'Failed to remove post type '.$this->pt;
            if (is_wp_error($result)) {
                $message = $this->getWpErrorMessage($result);
            }
            throw new PluginException($message);
            // @codeCoverageIgnoreEnd
        }
    }

    public function exist(): bool
    {
        return post_type_exists($this->pt);
    }

    public function getWpErrorMessage(\WP_Error $error): string
    {
        return $error->get_error_message();
    }
}
