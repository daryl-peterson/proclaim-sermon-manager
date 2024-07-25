<?php

namespace DRPSermonManager;

/**
 * Helper class.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class Helper
{
    /**
     * Create a key for options ect with the KEY_PREFIX.
     */
    public static function getKeyName(string $name, string $delimiter = '_'): string
    {
        $prefix = KEY_PREFIX;

        $name = trim(trim($name, '-_'));

        $len = strlen($prefix.$delimiter);
        if (strlen($name) > $len) {
            if (substr($name, 0, $len) === $prefix.$delimiter) {
                return $name;
            }
        }

        return strtolower($prefix.$delimiter.$name);
    }

    public static function getPluginDir(): string
    {
        return plugin_dir_path(FILE);
    }

    public static function getSlug(): string
    {
        return dirname(plugin_basename(FILE));
    }

    public static function getUrl(): string
    {
        return plugin_dir_url(FILE);
    }

    public static function getActivePlugins(): array
    {
        $plugins = (array) get_option('active_plugins');

        return $plugins;
    }

    public static function isCompatible(): bool
    {
        $transient = Helper::getKeyName('compatible');
        $result = (bool) get_transient($transient);

        return $result;
    }

    public static function isPluginActive(string $name): bool
    {
        // @codeCoverageIgnoreStart
        if (!function_exists('\is_plugin_active')) {
            $file = ABSPATH.'wp-admin/includes/plugin.php';
            Logger::debug("Including file: $file");
            require_once $file;
        }
        // @codeCoverageIgnoreEnd

        return is_plugin_active($name);
    }

    /**
     * Get transient.
     * - Key is changed to namespace prefix.
     */
    public static function getTransient($key): mixed
    {
        $transient = self::getKeyName($key);

        return get_transient($transient);
    }

    /**
     * Set transient.
     *
     * @param int $expire Seconds
     */
    public static function setTransient(string $key, mixed $value, int $expire = 0): bool
    {
        $transient = self::getKeyName($key);

        return set_transient($transient, $value, $expire);
    }

    public static function GmtToLocal(string $date)
    {
        $mdate = date('Y-m-d H:i:s', strtotime($date));

        $tz = new \DateTimeZone('GMT');
        $dt = new \DateTime($mdate, $tz);
        $tz = new \DateTimeZone(wp_timezone_string());
        $dt->setTimezone($tz);
        $mdate = $dt->format('Y-m-d');

        return $mdate;
    }
}
