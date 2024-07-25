<?php

namespace DRPSermonManager\Core\Interfaces;

/**
 * Class description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface RequirementsInterface
{
    public function isCompatible(): void;

    /**
     * Initailze class hooks.
     */
    public function init(): void;

    /**
     * Get notice interface.
     */
    public function notice(): NoticeInterface;

    /**
     * Get force fail.
     */
    public function setFail(bool $fail): void;
}
