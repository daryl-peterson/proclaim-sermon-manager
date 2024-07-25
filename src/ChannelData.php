<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Abstracts\VimeoDataAbstract;

/**
 * Vimeo channel data structure.
 *
 * @author Daryl Peterson
 *
 * @property int    $id            Channel ID
 * @property string $uri           URI of the channel
 * @property string $name          Channel name
 * @property string $description   Channel description
 * @property string $link          Channel link
 * @property string $created_time  Time channel was created
 * @property string $modified_time Time channel was modified
 */
class ChannelData extends VimeoDataAbstract
{
    protected function init()
    {
        if (isset($this->uri)) {
            $this->id = str_replace('/channels/', '', $this->uri);
        }
    }

    public static function getFields()
    {
        return $fields = [
            'uri',
            'name',
            'description',
            'privacy.view',
            'link',
            'created_time',
            'modified_time',
        ];
    }
}
