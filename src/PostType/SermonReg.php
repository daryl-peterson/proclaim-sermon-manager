<?php
/**
 * Sermon post type registration.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\PostType;

use DRPSermonManager\Abstracts\PostTypeRegAbs;
use DRPSermonManager\Constants\PT;
use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Helper;
use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Logging\Logger;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

/**
 * Sermon post type registration.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class SermonReg extends PostTypeRegAbs implements PostTypeRegInt {

	/**
	 * Set object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->pt          = PT::SERMON;
		$this->config_file = 'post-type-sermon.php';
	}

	/**
	 * Get initialize object.
	 *
	 * @return SermonReg
	 * @since 1.0.0
	 */
	public static function init(): SermonReg {
		$obj = new self();

		return $obj;
	}
}
