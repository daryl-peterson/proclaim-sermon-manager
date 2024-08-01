<?php

namespace DRPSermonManager\Abstracts;

use DRPSermonManager\Attachment;
use DRPSermonManager\Interfaces\PostMetaInt;
use DRPSermonManager\Logging\Logger;

/**
 * Post meta abstract.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
abstract class PostMetaAbs implements PostMetaInt
{
    /**
     * Meta name.
     * - Is used when input is rendered.
     *
     * @since 1.0.0
     */
    protected string $name;

    /**
     * Meta discription.
     * - Is used when input is rendered.
     *
     * @since 1.0.0
     */
    protected string $description;

    /**
     * Meta label.
     * - Is used when input is rendered.
     *
     * @since 1.0.0
     */
    protected string $label;

    /**
     * Taxonomy for meta.
     *
     * @since 1.0.0
     */
    protected string $taxonomy;

    /**
     * Attachment mime type.
     */
    protected string $mime;

    /**
     * CSS classes.
     */
    protected string $inputClass;

    public static function init(): PostMetaInt
    {
        return new static();
    }

    public function register(): void
    {
        return;
    }

    public function set(int $post_id): bool
    {
        try {
            $meta = $this->name;
            if (isset($_REQUEST[$meta])) {
                $value = sanitize_text_field($_REQUEST[$meta]);
                $value = apply_filters($this->name.'_meta_set', $value);
                $result = update_post_meta($post_id, $meta, $value);
            } else {
                $result = delete_post_meta($post_id, $meta);
            }
            $this->setAttachments();

            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);

            $result = false;
            // @codeCoverageIgnoreEnd
        }

        return boolval($result);
    }

    private function setAttachments()
    {
        if (!isset($this->mime)) {
            return;
        }

        $key = $this->name.'_list';
        if (!isset($_REQUEST[$key])) {
            return;
        }

        try {
            $items = $_REQUEST[$key];

            foreach ($items as $key => $value) {
                if (!isset($value) || empty($value)) {
                    wp_update_post([
                        'ID' => $key,
                        'post_parent' => 0,
                    ]);
                }
            }
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
            // @codeCoverageIgnoreEnd
        }
    }

    public function get(int $post_id): mixed
    {
        $value = get_post_meta($post_id, $this->name, true);
        $value = apply_filters($this->name.'_meta_get', $value);

        return $value;
    }

    public function getAttachments(int $post_id): ?array
    {
        if (!isset($this->mime)) {
            return null;
        }

        $result = Attachment::init()->get($post_id, $this->mime);

        return $result;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getTerms(): ?array
    {
        return null;
    }

    public function getDescription(): ?string
    {
        if (!isset($this->description)) {
            return null;
        }

        return $this->description;
    }

    public function hasTaxonomy(): bool
    {
        if (!isset($this->taxonomy) || empty($this->taxonomy)) {
            return false;
        }

        return true;
    }

    public function getTaxonomy(): ?string
    {
        if (!isset($this->taxonomy)) {
            return null;
        }

        return $this->taxonomy;
    }

    public function getInput(int $post_id): string
    {
        return '';
    }

    public function getInputClass(): ?string
    {
        if (!isset($this->inputClass)) {
            return null;
        }

        return $this->inputClass;
    }
}
