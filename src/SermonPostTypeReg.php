<?php

namespace DRPSermonManager;

use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Interfaces\SermonPostTypeRegInt;

/**
 * Sermon post type registration.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class SermonPostTypeReg implements SermonPostTypeRegInt
{
    /**
     * Post type.
     */
    private string $pt;

    protected function __construct()
    {
        $this->pt = Constant::POST_TYPE_SERMON;
    }

    public static function init(): SermonPostTypeRegInt
    {
        $obj = new self();

        return $obj;
    }

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
            $def = Helper::getConfig('sermon_post_type.php');
            $result = register_post_type($this->pt, $def);
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            FatalError::set($th);
            // @codeCoverageIgnoreEnd
        }

        if (is_wp_error($result) || !($result instanceof \WP_Post_Type)) {
            // @codeCoverageIgnoreStart
            $message = 'Failed to add post type '.$this->pt;
            throw new PluginException($message);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Remove post type.
     */
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
            FatalError::set($th);
            // @codeCoverageIgnoreEnd
        }

        if (is_wp_error($result) || !$result) {
            // @codeCoverageIgnoreStart
            $message = 'Failed to remove post type '.$this->pt;
            throw new PluginException($message);
            // @codeCoverageIgnoreEnd
        }
    }

    private function exist()
    {
        return post_type_exists($this->pt);
    }
}
