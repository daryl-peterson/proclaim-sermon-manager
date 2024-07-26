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
interface OptionsInterface
{
    /**
     * Get option.
     */
    public function get(string $name, mixed $default = null): mixed;

    /**
     * Set option.
     */
    public function set(string $name, $value = null): bool;

    /**
     * Delete option.
     */
    public function delete(string $name): bool;
}
