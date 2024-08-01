<?php

namespace DRPSermonManager\PostMeta;

use DRPSermonManager\Abstracts\PostMetaAbs;
use DRPSermonManager\Constant;

use const DRPSermonManager\DOMAIN;

defined('ABSPATH') or exit;

/**
 * Sermon audio meta.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class SermonAudio extends PostMetaAbs
{
    protected function __construct()
    {
        $this->name = Constant::META_AUDIO;
        $this->label = __('Sermon Audio', DOMAIN);
    }
}
