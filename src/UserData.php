<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Abstracts\VimeoDataAbstract;
use DRPSermonManager\Core\Exceptions\VimeoException;

/**
 * Data reduced structure of vimeo user.
 *
 * @since 1.0.0
 *
 * @author Daryl Peterson
 *
 * @property int    $id      ID part of user URI
 * @property string $uri     Vimeo user URI
 * @property string $name    Vimeo user name
 * @property string $link    Vimeo user link
 * @property string $account Account type
 */
class UserData extends VimeoDataAbstract
{
    protected function init()
    {
        if (isset($this->uri)) {
            $this->id = str_replace('/users/', '', $this->uri);
        }
    }

    public static function getFields()
    {
        return $fields = [
            'id',
            'uri',
            'name',
            'link',
            'account',
        ];
    }

    /**
     * Check if variable is user.
     * - exception=true will throw exception if variable is not a user.
     * - exception=false will return bool.
     */
    public static function isUser(mixed $user, bool $exception = false): bool
    {
        if (!$user instanceof UserData) {
            if ($exception) {
                throw new VimeoException('Unable to locate user');
            }

            return false;
        }

        return true;
    }
}
