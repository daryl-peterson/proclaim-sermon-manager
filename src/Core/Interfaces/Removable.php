<?php

namespace DRPSermonManager\Interfaces;

/**
 * Removable interface.
 * - Has remove method with no parmaters.
 * - Returns void.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface Removable
{
    /**
     * Generic method to remove something.
     *
     * @since 1.0.0
     */
    public function remove(): void;
}
