<?php

namespace DRPSermonManager\Interfaces;

use DRPSermonManager\Exceptions\PluginException;

/**
 * Sermon post type register.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface SermonPostTypeRegInt extends Initable
{
    /**
     * Add post type.
     *
     * @throws PluginException
     */
    public function add(): void;

    /**
     * Remove post type.
     *
     * @throws PluginException
     */
    public function remove(): void;
}
