<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Interfaces\OptionsInterface;
use DRPSermonManager\Core\Interfaces\SermonVideoInterface;
use DRPSermonManager\Core\Interfaces\VideoInterface;

/**
 * Sermon abstract.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class SermonVideo implements SermonVideoInterface
{
    protected string $postType;
    protected VideoInterface $video;
    protected OptionsInterface $options;
    protected string $match;

    public function __construct(VideoInterface $video, OptionsInterface $options)
    {
        $this->postType = PLUGIN_SM_SERMON;
        $this->video = $video;
        $this->options = $options;
        $this->match = $this->video->getMatch();
    }

    public function setMatching(string $match): void
    {
        $this->match = $match;
    }

    public function getMatching(): string
    {
        return $this->match;
    }

    public function getVideo(\WP_Post $post): bool|VideoData
    {
        try {
            $video = $this->getByCondition($post);
            VideoData::isVideo($video, true);
            SermonUtils::isMatch($post, $video, $this->match);

            return $video;
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function getByCondition(\WP_Post $post): ?VideoData
    {
        try {
            $result = null;

            switch ($this->match) {
                case 'name':
                    $result = $this->video->getByName($post->post_title);
                    break;

                case 'date':
                    $date = SermonUtils::getSermonDate($post);
                    $result = $this->video->getRecent($date);
                    break;
            }
            Logger::debug([
                'MATCHING' => $this->match,
                'POST' => $post,
                'VIDEO' => $result,
            ]);

            return $result;
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            return null;
            // @codeCoverageIgnoreEnd
        }
    }
}
