<?php
/**
 * Taxonomy bible book registration.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Taxonomy;

use DRPSermonManager\Abstracts\TaxonomyRegAbs;
use DRPSermonManager\Constants\PT;
use DRPSermonManager\Constants\TAX;
use DRPSermonManager\Interfaces\TaxonomyRegInt;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

/**
 * Taxonomy bible book registration.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class BibleBookReg extends TaxonomyRegAbs implements TaxonomyRegInt {

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->taxonomy    = TAX::BIBLE_BOOK;
		$this->post_type   = PT::SERMON;
		$this->config_file = 'taxonomy-bible-book.php';
	}
}
