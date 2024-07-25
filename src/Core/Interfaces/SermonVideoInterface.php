<?php

namespace DRPSermonManager\Core\Interfaces;

use DRPSermonManager\VideoData;

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
interface SermonVideoInterface
{
    public function __construct(VideoInterface $video, OptionsInterface $options);

    /**
     * Set matching condition.
     */
    public function setMatching(string $match): void;

    /**
     * Get matching condition.
     */
    public function getMatching(): string;

    /**
     * Get Video.
     */
    public function getVideo(\WP_Post $post): bool|VideoData;
}
