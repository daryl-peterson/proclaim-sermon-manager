<?php

namespace DRPSermonManager\Abstracts;

use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Helper;
use DRPSermonManager\Interfaces\TaxonomyRegInt;
use DRPSermonManager\Logging\Logger;

/**
 * Abstract taxonomy registration interface.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
abstract class TaxonomyRegAbs implements TaxonomyRegInt
{
    /**
     * Taxonomy.
     */
    protected string $taxonomy;

    /**
     * Post Type.
     */
    protected string $postType;

    /**
     * Congifurage file to read.
     */
    protected string $configFile;

    abstract protected function __construct();

    public static function init(): TaxonomyRegInt
    {
        return new static();
    }

    public function add(): void
    {
        $exist = $this->exist();
        $result = false;
        if (!defined('PHPUNIT_TESTING')) {
            // @codeCoverageIgnoreStart
            if (!is_blog_installed() || $exist) {
                return;
            }
            // @codeCoverageIgnoreEnd
        }

        try {
            $def = Helper::getConfig($this->configFile);
            $result = register_taxonomy($this->taxonomy, [$this->postType], $def);
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
            // @codeCoverageIgnoreEnd
        }

        if (!$this->exist() || is_wp_error($result)) {
            // @codeCoverageIgnoreStart
            $message = 'Failed to add taxonomy '.$this->taxonomy;
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
            $result = unregister_taxonomy($this->taxonomy);
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
            // @codeCoverageIgnoreEnd
        }

        if ($this->exist() || is_wp_error($result)) {
            // @codeCoverageIgnoreStart
            $message = 'Failed to remove taxonomy '.$this->taxonomy;
            if (is_wp_error($result)) {
                $message = $this->getWpErrorMessage($result);
            }
            throw new PluginException($message);
            // @codeCoverageIgnoreEnd
        }
    }

    public function exist(): bool
    {
        return taxonomy_exists($this->taxonomy);
    }

    public function getWpErrorMessage(\WP_Error $error): string
    {
        return $error->get_error_message();
    }
}
