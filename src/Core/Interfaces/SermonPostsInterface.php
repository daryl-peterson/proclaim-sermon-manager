<?php

namespace DRPSermonManager\Core\Interfaces;

/**
 * Interface description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface SermonPostsInterface
{
    /**
     * Initialize hooks.
     */
    public function init(): void;

    /**
     * Save Post.
     */
    public function savePost(int $post_id, \WP_Post $post, bool $update): bool;

    /**
     * Publish sermon.
     */
    public function publishSermon(string $new_status, string $old_status, \WP_Post $post): bool;

    /**
     * Get sermon video interface.
     */
    public function video(): SermonVideoInterface;
}
