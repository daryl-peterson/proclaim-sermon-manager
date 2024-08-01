<?php

namespace DRPSermonManager\PostMeta;

use DRPSermonManager\Abstracts\PostMetaAbs;
use DRPSermonManager\Constant;
use DRPSermonManager\Logging\Logger;
use DRPSermonManager\Options;

use const DRPSermonManager\DOMAIN;

/**
 * Sermon date post meta.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class SermonDate extends PostMetaAbs
{
    protected function __construct()
    {
        $this->name = Constant::META_DATE;
        $this->label = __('Date Preached', DOMAIN);
        $this->description = 'format: mm/dd/yyyy';
    }

    public static function init(): SermonDate
    {
        return new self();
    }

    public function register(): void
    {
        // $id_value = apply_filters($field_id.'_meta_get', $id_value);
        add_filter(Constant::META_DATE.'_meta_get', [$this, 'filter'], 10, 2);
    }

    public function get(int $post_id): mixed
    {
        $date = get_post_meta($post_id, $this->name, true);

        $opts = Options::init();
        $localize = $opts->get($this->name.'_localize', true);
        $format = $opts->get($this->name.'_format', '');

        if (!$this->hasTime($date)) {
            return $date;
        }

        if (empty($format)) {
            $format = get_option('date_format', 'U');
        }

        $date = $localize ? date_i18n($format, $date) : date($format, $date);

        return $date;
    }

    public function set(int $post_id): bool
    {
        try {
            $meta = Constant::META_DATE;
            $date = false;
            if (isset($_REQUEST[$meta])) {
                $date = date('m/d/Y', strtotime(sanitize_text_field($_REQUEST[$meta])));
                $date = strtotime($date);
                $result = update_post_meta($post_id, $meta, $date);
            } else {
                $result = delete_post_meta($post_id, $meta);
            }

            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);

            return false;
            // @codeCoverageIgnoreEnd
        }

        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * Get sermon date preached.
     *
     * Get format from options
     * 1. Specific date format {$this->name}_format
     * 2. Default date format date_format
     *
     * Called from
     * - apply_filer {$this->name.'_meta_get'}
     *
     * @since 1.0
     */
    public function filter(mixed $date, int $post_id): mixed
    {
        $opts = Options::init();
        $localize = $opts->get($this->name.'_localize', true);
        $format = $opts->get($this->name.'_format', '');

        if (!$this->hasTime($date)) {
            return $date;
        }

        if (empty($format)) {
            $format = get_option('date_format', 'U');
        }

        $date = $localize ? date_i18n($format, $date) : date($format, $date);

        return $date;
    }

    /**
     * Check if the date is set.
     *
     * @since 1.0.0
     */
    private function hasTime(mixed $date): bool
    {
        if (empty($date) || !isset($date)) {
            return false;
        }

        return true;
    }
}
