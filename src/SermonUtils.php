<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Exceptions\VimeoException;

/**
 * Sermon utilities.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 */
class SermonUtils
{
    /**
     * Check if video is a match for sermon.
     */
    public static function isMatch(\WP_Post $post, VideoData $video, string $matching): void
    {
        try {
            $isMatch = false;
            $date_sermon = $date_video = '';
            switch ($matching) {
                case 'name':
                    if ($video->name === $post->post_title) {
                        $isMatch = true;
                    }

                    break;
                case 'date':
                    // @todo fix
                    $date_sermon = self::getSermonDate($post);
                    $date_video = Helper::GmtToLocal($video->modified_time);

                    if ($date_sermon === $date_video) {
                        $isMatch = true;
                    }

                    break;
            }

            Logger::debug([
                'POST TITLE' => $post->post_title,
                'VIDEO TITLE' => $video->name,
                'DATE SERMON' => $date_sermon,
                'DATE VIDEO' => $date_video,
                'MATCHING' => $matching,
                'IS MATCH' => $isMatch,
                'POST' => $post,
            ]);

            if (!$isMatch) {
                throw new VimeoException('No match found');
            }
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            throw $th;
            // @codeCoverageIgnoreEnd
        }
    }

    public static function getSermonDate(\WP_Post $post): string
    {
        $key = 'sermon_date';
        $result = get_post_meta($post->ID, $key, true);
        if (isset($result) || !empty($result)) {
            $date = $result;
        } else {
            // @codeCoverageIgnoreStart
            $date = $post->post_date;
            // @codeCoverageIgnoreEnd
        }
        $date = date('Y-m-d', $date);

        return $date;
    }

    public static function hasVideoLink(\WP_Post $post, bool $exception = false): bool
    {
        $key = 'sermon_video_link';

        $result = get_post_meta($post->ID, $key, true);

        $res = $exception ? 'true' : 'false';
        Logger::debug([
            'HAS VIDEO LINK' => [
                'POST' => $post,
                'SERMON LINK' => $result,
                'EXCEPTION' => $res,
            ],
        ]);

        if (isset($result) && !empty($result)) {
            if ($exception) {
                throw new VimeoException('Video Link present');
            }

            // @codeCoverageIgnoreStart
            return true;
            // @codeCoverageIgnoreEnd
        }

        return false;
    }
}
