<?php

namespace DRPSermonManager\Core\Interfaces;

use DRPSermonManager\Collection;
use DRPSermonManager\VideoData;

/**
 * Video interface.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface VideoInterface
{
    /**
     * Get video by name.
     */
    public function getByName(string $name): ?VideoData;

    /**
     * Get the most recent video.
     */
    public function getRecent(string $date = ''): ?VideoData;

    /**
     * Get video count.
     */
    public function getCount(): ?int;

    /**
     * Get all videos that have a valid duration.
     */
    public function getAll(): ?Collection;

    /**
     * Get Vimeo video list.
     *
     * @param string $last_modified Date
     */
    public function getVideos(array $args = [], ?string $last_modified = null): ?Collection;

    /**
     * Get matching condition.
     */
    public function getMatch(): string;

    /**
     * Make API request.
     */
    public function get(string $endpoint, array $params = [], string $method = 'GET', ?string $last_modified = null): ?array;

    public function put($endpoint, array $args): ?array;
}
