<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Interfaces\SermonPostsInterface;
use DRPSermonManager\Core\Interfaces\SermonVideoInterface;

/**
 * Sermon post save and publish.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class SermonPost implements SermonPostsInterface
{
    protected string $postType;
    public SermonVideoInterface $video;

    public function __construct(SermonVideoInterface $video)
    {
        $this->postType = PLUGIN_SM_SERMON;
        $this->video = $video;
    }

    public function init(): void
    {
        try {
            $hook = Helper::getKeyName('SERMON_POST_INIT');
            if (did_action($hook) && !defined('PHPUNIT_TESTING')) {
                // @codeCoverageIgnoreStart
                return;
                // @codeCoverageIgnoreEnd
            }
            add_action('save_post_'.$this->postType, [$this, 'savePost'], 30, 3);
            add_action('transition_post_status', [$this, 'publishSermon'], 10, 3);
            Logger::debug('SERMON POST HOOKS INITIALIZED');
            do_action($hook);
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
            // @codeCoverageIgnoreEnd
        }
    }

    public function video(): SermonVideoInterface
    {
        return $this->video;
    }

    public function savePost(int $post_id, \WP_Post $post, bool $update): bool
    {
        try {
            PostValidation::isValidPostType($post, $this->postType, true);

            Logger::debug([
                'SAVE POST' => [
                    'POST ID' => $post_id,
                    'POST' => $post,
                    'UPDATE' => $update,
                    ],
                ]);

            // @codeCoverageIgnoreStart
            if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
                Logger::debug('EXITING HERE');

                return false;
            }
            // @codeCoverageIgnoreEnd

            SermonUtils::hasVideoLink($post, true);
            $video = $this->video->getVideo($post);
            VideoData::isVideo($video, true);
            $_POST['sermon_video_link'] = $video->link;

            return true;
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            // Logger::error(['ERROR' => $th->getMessage(), 'TRACE' => $th->getTrace()]);

            return false;
            // @codeCoverageIgnoreEnd
        }
    }

    public function publishSermon(string $new_status, string $old_status, \WP_Post $post): bool
    {
        try {
            PostValidation::isValidPostType($post, $this->postType, true);
            PostValidation::isValidPostStatus($new_status, $old_status, true);

            SermonUtils::hasVideoLink($post, true);
            $video = $this->video->getVideo($post);
            VideoData::isVideo($video, true);
            $key = 'sermon_video_link';
            $result = add_post_meta($post->ID, $key, $video->link);
            if (!$result) {
                return false;
            }

            return (bool) $result;
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            // Logger::error(['ERROR' => $th->getMessage(), 'TRACE' => $th->getTrace()]);

            return false;
            // @codeCoverageIgnoreEnd
        }
    }
}
