<?php

namespace DRPSermonManager\Taxonomy;

use DRPSermonManager\Abstracts\TaxonomyRegAbs;
use DRPSermonManager\Constants\PT;
use DRPSermonManager\Constants\TAX;

defined('ABSPATH') or exit;

/**
 * Taxonomy topics registration.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class TopicsReg extends TaxonomyRegAbs
{
    protected function __construct()
    {
        $this->taxonomy = TAX::TOPICS;
        $this->postType = PT::SERMON;
        $this->configFile = 'taxonomy_topics.php';
    }
}
