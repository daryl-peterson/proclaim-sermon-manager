<?php

namespace DRPSermonManager\Core\Interfaces;

/**
 * Text Domain translation interface.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface TextDomainInterface extends Initable
{
    /**
     * Switch to site language.
     *
     * @since 1.0
     */
    public function switchToSiteLocale(): void;

    /**
     * Restore language to original.
     *
     * @since 1.0
     */
    public function restoreLocale(): void;
}
