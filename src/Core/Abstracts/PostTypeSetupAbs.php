<?php

namespace DRPSermonManager\Abstracts;

use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Interfaces\PostTypeSetupInt;

/**
 * Post type setup abstract.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
abstract class PostTypeSetupAbs implements PostTypeSetupInt
{
    /**
     * Taxonomies indexed on post type.
     */
    protected array $taxonomies;

    /**
     * Post types.
     */
    protected array $postypes;

    public static function init(): PostTypeSetupInt
    {
        return new static();
    }

    public function getPostTypeList(): array
    {
        return array_keys($this->postypes);
    }

    public function getPostType(string $post_type): PostTypeRegInt
    {
        if (!isset($this->postypes[$post_type])) {
            throw new PluginException("Invalid post type : $post_type");
        }

        return $this->postypes[$post_type];
    }

    public function getPostTypeTaxonomies(string $post_type): ?array
    {
        if (!isset($this->taxonomies[$post_type])) {
            return null;
        }

        return $this->taxonomies[$post_type];
    }
}
