<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Exceptions\VimeoException;

/**
 * Post validation functions.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class PostValidation
{
    /**
     * Validate post type
     * - exception = false - Will return bool
     * - exception = true - Will throw exception if not expected type.
     */
    public static function isValidPostType(\WP_Post $post, string $expected_type, bool $exception = false): bool
    {
        Logger::debug([
            'POST TYPE' => $post->post_type,
            'EXPECTED TYPE' => $expected_type,
            'EXCEPTION' => $exception ? 'true' : 'false',
        ]);
        if ($post->post_type !== $expected_type) {
            if ($exception) {
                throw new VimeoException("Invalid post type: $post->post_type");
            }

            return false;
        }

        return true;
    }

    /**
     * Make sure the post status has changed and is not published.
     * - exception = false - Will return bool
     * - exception = true - Will throw exception if status has not changed.
     */
    public static function isValidPostStatus(string $new, string $old, bool $exception = false): bool
    {
        Logger::debug([
            'STATUS NEW' => $new,
            'STATUS OLD' => $old,
            'EXCEPTION' => $exception ? 'true' : 'false',
        ]);

        if (('publish' !== $new) || ('publish' === $old)) {
            if ($exception) {
                throw new VimeoException('Invalid post status');
            }

            return false;
        }

        return true;
    }
}
