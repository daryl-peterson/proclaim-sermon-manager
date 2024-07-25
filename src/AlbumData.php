<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Abstracts\VimeoDataAbstract;

/**
 * Vimeo album data structure.
 *
 * @property int    $id            Video ID
 * @property string $uri           Video URI
 * @property string $name          Video name
 * @property string $description   Description
 * @property string $link          Video link
 * @property int    $duration      Video duration
 * @property string $created_time  Creation time
 * @property string $modified_time Modification time
 *
 * @author Daryl Peterson
 */
class AlbumData extends VimeoDataAbstract
{
    protected function init()
    {
        if (isset($this->uri)) {
            $parts = explode('/', $this->uri);
            $this->id = end($parts);
        }
    }

    public static function getFields(): array
    {
        return $fields = [
            'uri',
            'name',
            'description',
            'link',
            'duration',
            'created_time',
            'modified_time',
        ];
    }
}
