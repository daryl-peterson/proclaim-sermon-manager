<?php

namespace DRPSermonManager;

use DRPSermonManager\Abstracts\TaxonomyRegAbs;

/**
 * Preacher taxonomy.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class TaxPreacherReg extends TaxonomyRegAbs
{
    public function __construct()
    {
        $this->taxonomy = Constant::TAX_PREACHER;
        $this->postType = Constant::POST_TYPE_SERMON;
        $this->configFile = 'taxonomy_preacher.php';
    }
}
