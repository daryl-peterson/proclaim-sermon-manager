<?php

namespace DRPSermonManager\Taxonomy;

use DRPSermonManager\Interfaces\Initable;
use DRPSermonManager\Interfaces\Runable;

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
class BibleBookLoad implements Initable, Runable
{
    protected function __construct()
    {
        // Code Here
    }

    public static function init(): BibleBookLoad
    {
        return new self();
    }

    public function run(): void
    {
    }
}
