<?php

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Interfaces\SermonPostTypeRegInt;

/**
 * Register post types and taxonomies.
 * - Stub - calls other object methods.
 * - Used so other object don't have to register callbacks.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class PostTypeReg implements PostTypeRegInt
{
    private SermonPostTypeRegInt $sermon;

    protected function __construct()
    {
        $this->sermon = SermonPostTypeReg::init();
    }

    public static function init(): PostTypeRegInt
    {
        return new self();
    }

    public function register(): void
    {
        add_action('init', [$this, 'add']);
    }

    public function add(): void
    {
        try {
            $this->sermon->add();
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            FatalError::set($th);
            // @codeCoverageIgnoreEnd
        }
    }

    public function remove(): void
    {
        try {
            $this->sermon->remove();
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            FatalError::set($th);
            // @codeCoverageIgnoreEnd
        }
    }

    public function flush()
    {
    }
}
