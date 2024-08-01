<?php

namespace DRPSermonManager\PostMeta;

use DRPSermonManager\Abstracts\PostMetaAbs;
use DRPSermonManager\Constant;

use const DRPSermonManager\DOMAIN;

defined('ABSPATH') or exit;

/**
 * Sermon service type meta.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class ServiceType extends PostMetaAbs
{
    protected function __construct()
    {
        $this->name = Constant::META_SERVICE_TYPE;
        $this->label = __('Service Type', DOMAIN);
        $this->taxonomy = Constant::TAX_SERVICE_TYPE;
    }

    public function getTerms(): ?array
    {
        $terms = get_terms(
            [
                'taxonomy' => $this->taxonomy,
                'hide_empty' => false,
                'orderby' => 'name',
                'order' => 'ASC',
            ]
        );

        if ($terms instanceof \WP_Error) {
            return null;
        }

        return (array) $terms;
    }
}
