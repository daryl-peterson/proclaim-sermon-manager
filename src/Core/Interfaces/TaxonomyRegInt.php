<?php

namespace DRPSermonManager\Interfaces;

use DRPSermonManager\Exceptions\PluginException;

/**
 * Taxonomy registration interface.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface TaxonomyRegInt extends Initable
{
    /**
     * Add taxonomy.
     *
     * @since 1.0.0
     *
     * @throws PluginException
     */
    public function add(): void;

    /**
     * Remove taxonomy.
     *
     * @since 1.0.0
     *
     * @throws PluginException
     */
    public function remove(): void;

    /**
     * Check if taxonomy exist.
     *
     * @since 1.0.0
     */
    public function exist(): bool;

    /**
     * Get WP_Error message.
     *
     * @since 1.0.0
     */
    public function getWpErrorMessage(\WP_Error $error): string;
}
