<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Exceptions\VimeoException;

/**
 * Vimeo response verifcation.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class VimeoResponse
{
    public static string $domain = DOMAIN;

    /**
     * Verify API response.
     *
     * @throws \VimeoException
     */
    public static function verify(array $response): void
    {
        $error = '';
        if (isset($response['body']['error'])) {
            $error = ' '.$response['body']['error'];
        }

        $status = (int) $response['status'];

        if ($status < 400) {
            return;
        }
        $codes = [
            400 => __('A bad request made was made. ', self::$domain),
            401 => __('An invalid token was used for the API request. ', self::$domain),
            403 => __('Your server\'s IP address is currently banned from using the Vimeo API.', self::$domain),
            404 => __('The plugin could not retrieve data from the Vimeo API! ', self::$domain),
            429 => __('Too many requests to Vimeo, please wait a moment and try again.', self::$domain),
            500 => __('Looks like Vimeo is having some API issues. Try reloading, or, check back in a few minutes.', self::$domain),
        ];
        if (isset($codes[$status])) {
            throw new VimeoException($codes[$status]);
        }
        throw new VimeoException($error);
    }

    /**
     * Make sure the response has data key.
     *
     * @throws VimeoException
     */
    public static function hasData(mixed $response): void
    {
        if (!isset($response) || !is_array($response) || !isset($response['body']['data'])) {
            // @codeCoverageIgnoreStart
            throw new VimeoException('Unable to locate requested item');
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Make sure the response has body key.
     *
     * @throws VimeoException
     */
    public static function hasBody(mixed $result)
    {
        if (!isset($result) || !is_array($result) || !isset($result['body'])) {
            // @codeCoverageIgnoreStart
            throw new VimeoException('Unable to locate requested item');
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Check if response has error key.
     *
     * @throws VimeoException
     */
    public static function hasError(mixed $result)
    {
        if (!isset($result) || !is_array($result) || isset($result['body']['error'])) {
            // @codeCoverageIgnoreStart
            throw new VimeoException('Unable to locate requested item');
            // @codeCoverageIgnoreEnd
        }
    }
}
