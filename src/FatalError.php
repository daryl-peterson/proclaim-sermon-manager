<?php

namespace DRPSermonManager;

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
class FatalError
{
    public static function set(\Throwable $th)
    {
        Logger::error(['MESSAGE' => $th->getMessage(), 'TRACE' => $th->getTrace()]);
        Deactivator::init()->run();
    }
}
