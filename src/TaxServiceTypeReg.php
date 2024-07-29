<?php

namespace DRPSermonManager;

use DRPSermonManager\Abstracts\TaxonomyRegAbs;
use DRPSermonManager\Interfaces\TaxonomyRegInt;

/**
 * Taxonomy service type registration.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class TaxServiceTypeReg extends TaxonomyRegAbs implements TaxonomyRegInt
{
    public function __construct()
    {
        $this->taxonomy = Constant::TAX_SERVICE_TYPE;
        $this->postType = Constant::POST_TYPE_SERMON;
        $this->configFile = 'taxonomy_service_type.php';
    }
}
