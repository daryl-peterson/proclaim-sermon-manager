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
class Channel extends VimeoAbstract
{
    /**
     * Get the users channels.
     */
    public function getChannels(): ?Collection
    {
        try {
            $user = $this->getUser(true);
            UserData::isUser($user, true);

            $endpoint = '/users/'.$user->id.'/channels';

            $args = [
                'fields' => $this->channelFields,
                'filter' => 'moderated',
            ];

            $response = $this->get($endpoint, $args);

            return $this->makeChannelCollection($response);

            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::debug(['ERROR' => $th->getMessage(), 'TRACE' => $th->getTrace()]);

            return null;
            // @codeCoverageIgnoreEnd
        }
    }

    private function makeChannelCollection(mixed $response): Collection
    {
        VimeoResponse::hasData($response);

        Logger::debug($response);

        $collection = new Collection();
        foreach ($response['body']['data'] as $info) {
            $obj = new ChannelData($info);
            $collection->push($obj);
        }

        return $collection;
    }
}
