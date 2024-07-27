<?php

namespace DRPSermonManager\Interfaces;

/**
 * Addable insterface.
 * - Has add method with no parameters.
 * - Returns void.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface Addable
{
    /**
     * Generic method add something.
     */
    public function add(): void;
}
