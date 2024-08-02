<?php

namespace DRPSermonManager\Taxonomy;

use DRPSermonManager\Abstracts\TaxonomyRegAbs;
use DRPSermonManager\Constants\PT;
use DRPSermonManager\Constants\TAX;
use DRPSermonManager\Interfaces\TaxonomyRegInt;

defined('ABSPATH') or exit;

/**
 * Taxonomy bible boog registration.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class BibleBookReg extends TaxonomyRegAbs implements TaxonomyRegInt
{
    public function __construct()
    {
        $this->taxonomy = TAX::BIBLE_BOOK;
        $this->postType = PT::SERMON;
        $this->configFile = 'taxonomy_bible_book.php';
    }
}
