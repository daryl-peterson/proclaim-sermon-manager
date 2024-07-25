<?php

namespace DRPSermonManager\Core\Abstracts;

use DRPSermonManager\AlbumData;
use DRPSermonManager\ChannelData;
use DRPSermonManager\Helper;
use DRPSermonManager\Logger;
use DRPSermonManager\UserData;
use DRPSermonManager\VideoData;
use DRPSermonManager\VimeoResponse;
use Vimeo\Vimeo as VimeoAPI;

use const DRPSermonManager\DOMAIN;
use const DRPSermonManager\NAME;

/**
 * Vimeo abstract.
 *
 * @author daryl
 */
abstract class VimeoAbstract
{
    protected VimeoAPI|null $api;

    protected $page;

    protected string $name;
    protected string $domain;
    protected string $videoFields;
    protected string $albumFields;
    protected string $channelFields;
    protected string $userFields;
    public string $match;

    /**
     * Object initialization.
     */
    public function __construct(string $client_id, string $client_secret, string $access_token, string $match)
    {
        $this->name = NAME;
        $this->domain = DOMAIN;

        $this->api = new VimeoAPI($client_id, $client_secret, $access_token);
        $this->match = $match;

        $this->videoFields = implode(',', VideoData::getFields());
        $this->albumFields = implode(',', AlbumData::getFields());
        $this->channelFields = implode(',', ChannelData::getFields());
        $this->userFields = implode(',', UserData::getFields());
    }

    public function isReady(bool $force_check = false): bool
    {
        try {
            if (!$force_check) {
                $ready = Helper::getTransient('ready');
                if ($ready) {
                    return true;
                }
            }

            $endpoint = '/tutorial';
            $response = $this->get($endpoint, []);
            VimeoResponse::verify($response);
            Helper::setTransient('ready', true, 300);

            return true;
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            return false;
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Get basic info on the current Vimeo user.
     */
    public function getUser($use_cache = false): ?UserData
    {
        try {
            if ($use_cache) {
                $user = Helper::getTransient('user');
                UserData::isUser($user, true);

                return $user;
            }

            $endpoint = '/me';
            $args = [
                'fields' => $this->userFields,
            ];

            $response = $this->get($endpoint, $args);

            $obj = new UserData($response['body']);
            if (isset($obj->uri)) {
                $parts = explode('/', $obj->uri);
                $id = end($parts);
                $obj->id = $id;
            }

            Helper::setTransient('user', $obj);

            return $obj;
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            return null;
            // @codeCoverageIgnoreEnd
        }
    }

    public function put($endpoint, array $args): ?array
    {
        // @codeCoverageIgnoreStart
        try {
            $result = $this->get($endpoint, $args, 'POST');

            return $result;
        } catch (\Throwable $th) {
            return null;
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Make API request.
     */
    public function get(string $endpoint, array $params = [], string $method = 'GET', ?string $last_modified = null): ?array
    {
        $result = null;

        try {
            $defaults = [
                'User-Agent' => sprintf('VimeoAgent (%s)', home_url()),
            ];

            if (null !== $last_modified) {
                // @codeCoverageIgnoreStart
                $defaults['If-Modified-Since'] = $last_modified;
                // @codeCoverageIgnoreEnd
            }

            $headers = apply_filters('video.request.headers', $defaults);
            $result = $this->api->request($endpoint, $params, $method, true, $headers);
            // Logger::debug($result);
            VimeoResponse::verify($result);

            return $result;
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            return null;
            // @codeCoverageIgnoreEnd
        }
    }
}
