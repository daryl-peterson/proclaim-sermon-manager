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
class User extends VimeoAbstract
{
    public function getUserById(int $id): ?UserData
    {
        try {
            $endpoint = "/users/$id";

            $args = [
                'fields' => $this->userFields,
            ];

            $response = $this->get($endpoint, $args);
            VimeoResponse::hasBody($response);

            $obj = new UserData($response['body']);
            if (isset($obj->uri)) {
                $parts = explode('/', $obj->uri);
                $id = end($parts);
                $obj->id = $id;
            }

            return $obj;
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['ERROR' => $th->getMessage(), 'TRACE' => $th->getTrace()]);

            return null;
            // @codeCoverageIgnoreEnd
        }
    }
}
