<?php

namespace DRPSermonManager\Facades;

use DRPSermonManager\Interfaces\OptionsInt;
use DRPSermonManager\Options;

/**
 * Get the current option interface.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class OptionsSvc
{
    /**
     * Get options interface.
     *
     * @since 1.0.0
     */
    public static function get(): OptionsInt
    {
        return Options::init();
    }
}
