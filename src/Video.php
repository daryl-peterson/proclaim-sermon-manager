<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Abstracts\VimeoAbstract;
use DRPSermonManager\Core\Interfaces\VideoInterface;

/**
 * Class description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class Video extends VimeoAbstract implements VideoInterface
{
    public function getByName(string $name): ?VideoData
    {
        try {
            $args = [
                'sort' => 'date',
                'direction' => 'desc',
                'query' => $name,
            ];

            $response = $this->getVideos($args);
            Collection::isCollection($response, true);
            $it = $response->getIterator();
            $it->rewind();

            $current = $it->current();
            Logger::debug(['ARGS' => $args, 'VIDEODATA' => $current]);

            return $current;
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            return null;
            // @codeCoverageIgnoreEnd
        }
    }

    public function getRecent(string $date = ''): ?VideoData
    {
        try {
            $args = [
                'sort' => 'date',
                'direction' => 'desc',
                'per_page' => 5,
            ];

            $response = $this->getVideos($args);
            Collection::isCollection($response, true);

            if (!empty($date)) {
                foreach ($response as $item) {
                    $mdate = Helper::GmtToLocal($item->modified_time);

                    // $mdate = date('Y-m-d', strtotime($item->modified_time));
                    Logger::debug(['ARGS' => $args, 'MDATE' => $mdate, 'DATE' => $date, 'ITEM' => $item]);
                    if ($mdate === $date) {
                        return $item;
                    }
                }
            } else {
                $it = $response->getIterator();
                $it->rewind();

                $current = $it->current();
                Logger::debug($current);

                return $current;
            }
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error($th);

            return null;
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Get video count.
     */
    public function getCount(): ?int
    {
        try {
            $args = [
                'per_page' => 1,
                'fields' => 'total',
            ];

            $response = $this->get('/me/videos', $args);

            return (int) $response['body']['total'];
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            return null;
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Get all videos that have a valid duration.
     */
    public function getAll(): ?Collection
    {
        try {
            $done = false;
            $page = 1;
            $response = false;
            $collection = new Collection();
            while (false === $done) {
                $args = [
                    'page' => $page,
                    'per_page' => 100,
                    'fields' => $this->videoFields,
                ];

                $response = $this->get('/me/videos', $args);
                $this->makeVideoCollection($collection, $response);

                if ($this->isPagingComplete($response)) {
                    break;
                }
                ++$page;
            }

            return $collection;
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            return null;
            // @codeCoverageIgnoreEnd
        }
    }

    public function getVideos(array $args = [], ?string $last_modified = null): ?Collection
    {
        $defaults = [
            'sort' => 'date',
            'direction' => 'desc',
            'per_page' => '10',
            'fields' => $this->videoFields,
        ];

        $params = array_merge($defaults, $args);

        try {
            $response = $this->get('/me/videos', $params, 'GET', $last_modified);
            $collection = new Collection();
            $this->makeVideoCollection($collection, $response);

            Logger::debug(['PARAMS' => $params, 'COLLECTION' => $collection]);

            return $collection;
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            return null;
            // @codeCoverageIgnoreEnd
        }
    }

    public function getMatch(): string
    {
        return $this->match;
    }

    private function isPagingComplete(mixed $response): bool
    {
        if (!is_array($response) || !isset($response['body']['paging']['next'])) {
            return true;
        }

        return false;
    }

    private function makeVideoCollection(Collection &$collection, mixed $response): void
    {
        VimeoResponse::hasData($response);

        foreach ($response['body']['data'] as $info) {
            $obj = new VideoData($info);

            if (0 != $obj->duration) {
                $collection->push($obj);
            }
        }
    }
}
