<?php

namespace DRPSermonManager\Interfaces;

/**
 * Plugin requirements.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface RequirementsInt extends Initable, Registrable
{
    /**
     * Check if plugin is compatible.
     */
    public function isCompatible(): void;

    /**
     * Get notice interface.
     *
     * @since 1.0.0
     */
    public function notice(): NoticeInt;

    /**
     * Get force fail.
     *
     * @since 1.0.0
     */
    public function setFail(bool $fail): void;
}
