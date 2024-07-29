<?php

namespace DRPSermonManager;

use DRPSermonManager\Abstracts\TaxonomyRegAbs;
use DRPSermonManager\Interfaces\TaxonomyRegInt;

/**
 * Taxonomy sermon series registration.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class TaxSeriesReg extends TaxonomyRegAbs implements TaxonomyRegInt
{
    protected function __construct()
    {
        $this->taxonomy = Constant::TAX_SERIES;
        $this->postType = Constant::POST_TYPE_SERMON;
        $this->configFile = 'taxonomy_series.php';
    }
}
