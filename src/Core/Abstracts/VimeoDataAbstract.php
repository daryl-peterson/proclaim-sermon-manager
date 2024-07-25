<?php

namespace DRPSermonManager\Core\Abstracts;

use DRPSermonManager\Core\Interfaces\VimeoDataInterface;
use DRPSermonManager\Logger;

/**
 * Vimeo data structure.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class VimeoDataAbstract implements VimeoDataInterface
{
    public ?int $id;
    public ?string $status;
    public ?string $name;
    public ?string $description;
    public ?string $uri;
    public ?string $type;
    public ?string $link;
    public ?int $duration;
    public ?string $created_time;
    public ?string $modified_time;
    public ?string $release_time;
    public ?string $embed_html;

    // User specific
    public ?string $account;

    public function __construct(array $data)
    {
        try {
            $this->id = 0;
            $fields = static::getFields();

            foreach ($fields as $field_name) {
                if (!key_exists($field_name, $data)) {
                    $this->getNested($field_name, $data);
                    continue;
                }

                if (property_exists($this, $field_name)) {
                    $this->$field_name = $data[$field_name];
                }
            }
            $this->init();

            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Finish initializing object properties.
     *
     * @since 1.0.0
     */
    abstract protected function init();

    private function getNested(string $field_name, array $data): void
    {
        try {
            if (strpos($field_name, '.') === false) {
                // @codeCoverageIgnoreStart
                return;
                // @codeCoverageIgnoreEnd
            }

            $parts = explode('.', $field_name);

            foreach ($parts as $key) {
                if (!key_exists($key, $data)) {
                    // @codeCoverageIgnoreStart
                    return;
                    // @codeCoverageIgnoreEnd
                }
                $data = $data[$key];
            }

            $this->setProperty($field_name, $data);

            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
        }
        // @codeCoverageIgnoreEnd
    }

    private function setProperty(string $field_name, mixed $data): void
    {
        // Logger::debug(['FIELD' => $field_name, 'DATA' => $data]);
        $field_name = str_replace('.', '_', $field_name);

        if (property_exists($this, $field_name)) {
            $this->$field_name = $data;
        } else {
            Logger::debug(['UNDEFINED PROPERTY' => $field_name]);
        }
    }
}
