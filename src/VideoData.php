<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Abstracts\VimeoDataAbstract;
use DRPSermonManager\Core\Exceptions\VimeoException;

/**
 * Vimeo video data structure.
 *
 * @author Daryl Peterson
 *
 * @property int    $id            Video ID
 * @property string $status
 * @property string $name          Video name
 * @property string $description   Description
 * @property string $uri           Video URI
 * @property string $type          Video type
 * @property string $link          Video link
 * @property int    $duration      Video duration
 * @property string $created_time  Creation time
 * @property string $modified_time Modification time
 * @property string $release_time  Release time
 * @property string $embed         HTML embed code
 */
class VideoData extends VimeoDataAbstract
{
    protected function init()
    {
        if (isset($this->uri)) {
            $this->id = str_replace('/videos/', '', $this->uri);
        }

        $date_fields = [
            'created_time',
            'modified_time',
            'release_time',
        ];

        foreach ($date_fields as $field) {
            if (isset($this->$field)) {
                $date = strtotime($this->$field);
                $this->$field = date(DATE_RFC7231, $date);
            }
        }
    }

    public static function getFields()
    {
        return $fields = [
            'uri',
            'name',
            'description',
            'type',
            'link',
            'duration',
            'status',
            'created_time',
            'modified_time',
            'release_time',
            'embed.html',
        ];
    }

    /**
     * Check if variable is of VideData.
     * - exception = false - Will return bool
     * - exception = true - Will throw exception if not VideoData.
     */
    public static function isVideo(mixed $video, bool $exception = false): bool
    {
        if (!$video | !$video instanceof VideoData) {
            if ($exception) {
                throw new VimeoException('No video found');
            }

            return false;
        }

        return true;
    }
}
