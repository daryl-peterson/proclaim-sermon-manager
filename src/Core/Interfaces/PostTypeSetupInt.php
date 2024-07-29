<?php

namespace DRPSermonManager\Interfaces;

use DRPSermonManager\Exceptions\PluginException;

/**
 * Post type registration interface.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface PostTypeSetupInt extends Initable, Registrable
{
    /**
     * Add post types and taxonomy.
     *
     * @since 1.0.0
     */
    public function add(): void;

    /**
     * Remove post types and taxonomy.
     *
     * @since 1.0.0
     */
    public function remove(): void;

    /**
     * Get list of post types.
     *
     * @since 1.0.0
     */
    public function getPostTypeList(): array;

    /**
     * Get post type.
     * - If post type does not exist throw exception.
     *
     * @since 1.0.0
     *
     * @throws PluginException;
     */
    public function getPostType(string $post_type): PostTypeRegInt;

    /**
     * Get post type taxonomies.
     *
     * @since 1.0.0
     */
    public function getPostTypeTaxonomies(string $post_type): ?array;

    /**
     * Flush rewrite rules soft.
     */
    public function flush(): void;
}
