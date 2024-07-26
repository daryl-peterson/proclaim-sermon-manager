<?php

namespace DRPSermonManager\Core\Interfaces;

/**
 * Has interface to initialize and get self.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface Initable
{
    public static function init(): self;
}
