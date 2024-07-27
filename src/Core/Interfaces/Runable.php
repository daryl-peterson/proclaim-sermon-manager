<?php

namespace DRPSermonManager\Interfaces;

use DRPSermonManager\Exceptions\PluginException;

/**
 * Runable interface. Run checks / service ect.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface Runable
{
    /**
     * Run checks / services.
     *
     * @since 1.0.0
     *
     * @throws PluginException
     */
    public function run(): void;
}
