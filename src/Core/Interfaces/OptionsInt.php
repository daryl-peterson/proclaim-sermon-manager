<?php

namespace DRPSermonManager\Interfaces;

/**
 * Options interface.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface OptionsInt extends Initable
{
    /**
     * Get option.
     *
     * @since 1.0.0
     */
    public function get(string $name, mixed $default = null): mixed;

    /**
     * Set option.
     *
     * @since 1.0.0
     */
    public function set(string $name, $value = null): bool;

    /**
     * Delete option.
     *
     * @since 1.0.0
     */
    public function delete(string $name): bool;
}
