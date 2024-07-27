<?php

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\OptionsInt;
use DRPSermonManager\Traits\SingletonTrait;

/**
 * Manage options (wp_options) settings.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @uses Helper::getKeyName
 *
 * @see https://developer.wordpress.org/reference/functions/get_option/
 * @see https://developer.wordpress.org/reference/functions/add_option/
 * @see https://developer.wordpress.org/reference/functions/update_option/
 */
class Options implements OptionsInt
{
    use SingletonTrait;

    public static function init(): OptionsInt
    {
        return Options::getInstance();
    }

    public function get(string $name, mixed $default = null): mixed
    {
        $option_name = Helper::getKeyName($name);

        return \get_option($option_name, $default);
    }

    public function set(string $name, $value = null): bool
    {
        try {
            $option_value = $this->get($name);

            if ($option_value === $value) {
                return true;
            }

            $option_name = Helper::getKeyName($name);

            if (!$option_value) {
                $result = \add_option($option_name, $value);
            } else {
                $result = \update_option($option_name, $value);
            }
            Logger::debug(['OPTION NAME' => $option_name, 'OPTION VALUE' => $value]);

            return $result;
            // @codeCoverageIgnoreStart
        } catch (\Throwable $th) {
            Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);

            return false;
        }
        // @codeCoverageIgnoreEnd
    }

    public function delete(string $name): bool
    {
        $option_name = Helper::getKeyName($name);

        return \delete_option($option_name);
    }
}
