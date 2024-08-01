<?php

namespace DRPSermonManager;

use DRPSermonManager\Logging\Logger;

/**
 * Attachment object.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class Attachment
{
    /**
     * Post id.
     */
    public int $id;

    /**
     * Parent id.
     */
    public int $parent;

    /**
     * Post date.
     */
    public string $date;

    /**
     * Mime type.
     */
    public string $mime;

    /**
     * File name.
     */
    public string $file;

    /**
     * File url.
     */
    public string $url;

    /**
     * Fize size.
     */
    public int $size;

    public static function init(): Attachment
    {
        return new self();
    }

    /**
     * Get an array of Attachments.
     */
    public function get(int $post_id, string $mime): ?array
    {
        try {
            $result = get_attached_media($mime, $post_id);
            if (count($result) < 1) {
                return null;
            }

            $return = [];

            /**
             * @var \WP_Post $post
             */
            foreach ($result as $post) {
                $post_array = $post->to_array();

                $meta = $this->getMeta((int) $post->ID);
                if (!isset($meta)) {
                    continue;
                }

                $post_array = array_merge($post_array, $meta);

                /**
                 * @var Attachment $obj
                 */
                $obj = $this->setObject($post_array, $mime);
                if (isset($obj)) {
                    $return[$obj->file] = $obj;
                }
            }

            $return = $this->sort($return);
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
            $return = null;
            // @codeCoverageIgnoreEnd
        }

        return $return;
    }

    private function getMeta(int $meta_id): ?array
    {
        try {
            $result = [];
            $path = $file = '';
            $size = 0;
            $attached = get_post_meta($meta_id, '_wp_attached_file', true);
            $meta = get_post_meta($meta_id, '_wp_attachment_metadata', true);
            $ds = DIRECTORY_SEPARATOR;

            if (empty($attached) | empty($meta)) {
                return null;
            }

            if (isset($attached)) {
                $info = pathinfo($attached);
                $file = $info['basename'];
                $upload_dir = wp_upload_dir();
                $path = $upload_dir['baseurl'].$ds.$info['dirname'].$ds.$file;

                /*
                Logger::debug([
                    'UPLOAD DIR' => $upload_dir,
                    'PATH INFO' => $info,
                    'FILE' => $file,
                    'URL' => $path,
                ]);
                */
            }

            if (isset($meta['filesize'])) {
                $size = (int) $meta['filesize'];
            }
            $result['file'] = $file;
            $result['url'] = $path;
            $result['size'] = $size;

            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
            $result = null;
            // @codeCoverageIgnoreEnd
        }

        return $result;
    }

    private function setObject(array $data, $mime): ?Attachment
    {
        $map = [
            'ID' => 'id',
            'post_parent' => 'parent',
            'post_date' => 'date',
            'post_mime_type' => 'mime',
            'file' => 'file',
            'url' => 'url',
            'size' => 'size',
        ];
        $casts = [
            'id' => 'int',
            'parent' => 'int',
            'date' => 'string',
            'mime' => 'string',
            'file' => 'string',
            'url' => 'string',
            'size' => 'int',
        ];

        try {
            $obj = new self();
            foreach ($map as $org_field => $new_field) {
                if (!isset($data[$org_field])) {
                    settype($obj->$new_field, $casts[$new_field]);
                    continue;
                }

                $tmp = $data[$org_field];
                if (isset($casts[$new_field])) {
                    $cast = $casts[$new_field];
                }
                /*
                Logger::debug(
                    ['TEMP' => $tmp,
                    'ORG FIELD' => $org_field,
                    'NEW FIELD' => $new_field,
                    'CAST' => $cast,
                ]);
                */
                settype($tmp, $casts[$new_field]);
                $obj->$new_field = $tmp;
            }

            $result = $obj;
            if ($obj->mime !== $mime) {
                $result = null;
            }

            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
            $result = null;
            // @codeCoverageIgnoreEnd
        }

        return $result;
    }

    private function sort(array $items): array
    {
        $result = [];
        if (count($items) > 0) {
            ksort($items);
            foreach ($items as $item) {
                $result[] = $item;
            }
        }

        return $result;
    }
}
