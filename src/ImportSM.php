<?php
/**
 * Import from sermon manager.
 *
 * @package     DRPPSM\ImportSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

/**
 * Import from sermon manager.
 *
 * @package     DRPPSM\ImportSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 *
 * @todo Finish implementing.
 */
class ImportSM {

	/**
	 * ImportSM constructor.
	 *
	 * @return void
	 *
	 * @todo Finish implementing.
	 */
	public function __construct() {
		// Code Here.
	}

	/**
	 * Import sermons from Sermon Manager.
	 *
	 * @return void
	 *
	 * @todo Finish implementing.
	 */
	public function import() {

		require_once ABSPATH . 'wp-admin/includes/import.php';
	}
}
