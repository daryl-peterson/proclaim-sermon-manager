<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Abstracts\VimeoAbstract;

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
class Album extends VimeoAbstract
{
    /**
     * Get user albums.
     */
    public function getAlbums(bool $use_cache = false): ?Collection
    {
        try {
            if ($use_cache) {
                $albums = Helper::getTransient('albums');
                if ($albums) {
                    return $albums;
                }
            }

            $endpoint = '/me/albums';
            $args = [
                'fields' => $this->albumFields,
            ];

            $response = $this->get($endpoint, $args);

            return $this->makeAlbumCollection($response);

            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            return null;
            // @codeCoverageIgnoreEnd
        }
    }

    private function makeAlbumCollection(mixed $response): Collection
    {
        VimeoResponse::hasData($response);

        $collection = new Collection();

        foreach ($response['body']['data'] as $info) {
            $obj = new AlbumData($info);
            $collection->push($obj);
        }
        Helper::setTransient('albums', $collection, 300);

        return $collection;
    }
}
