<?php

namespace DRPSermonManager\PostType;

use DRPSermonManager\Abstracts\PostTypeRegAbs;
use DRPSermonManager\Constant;
use DRPSermonManager\Interfaces\PostTypeRegInt;

defined('ABSPATH') or exit;

/**
 * Sermon post type registration.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class SermonReg extends PostTypeRegAbs implements PostTypeRegInt
{
    protected function __construct()
    {
        $this->pt = Constant::POST_TYPE_SERMON;
        $this->configFile = 'post_type_sermon.php';
    }

    public static function init(): PostTypeRegInt
    {
        $obj = new self();

        return $obj;
    }
}
