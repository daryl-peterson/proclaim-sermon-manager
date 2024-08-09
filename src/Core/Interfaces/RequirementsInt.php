<?php
/**
 * Requirments interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Interfaces;

/**
 * Requirments interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
interface RequirementsInt extends Registrable {

	/**
	 * Check if plugin is compatible.
	 *
	 * @return bool True if no errors.
	 * @since 1.0.0
	 */
	public function is_compatible(): bool;

	/**
	 * Get notice interface.
	 *
	 * @return NoticeInt
	 * @since 1.0.0
	 */
	public function notice(): NoticeInt;
}
