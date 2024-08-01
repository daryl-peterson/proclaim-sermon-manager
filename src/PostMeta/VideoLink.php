<?php

namespace DRPSermonManager\PostMeta;

use DRPSermonManager\Abstracts\PostMetaAbs;
use DRPSermonManager\Constant;

use const DRPSermonManager\DOMAIN;

defined('ABSPATH') or exit;

/**
 * Sermon video link meta.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class VideoLink extends PostMetaAbs
{
    protected function __construct()
    {
        $this->name = Constant::META_VIDEO_LINK;
        $this->label = __('Video Link', DOMAIN);
    }
}
