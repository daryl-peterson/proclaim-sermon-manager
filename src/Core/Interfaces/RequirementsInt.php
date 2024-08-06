<?php
/**
 * Requirments interface.
 *
 * @package     Proclain Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since 1.0.0
 */

namespace DRPSermonManager\Interfaces;

/**
 * Requirments interface.
 *
 * @package     Proclain Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since 1.0.0
 */
interface RequirementsInt {

	/**
	 * Check if plugin is compatible.
	 */
	public function is_compatible(): void;

	/**
	 * Get notice interface.
	 *
	 * @since 1.0.0
	 */
	public function notice(): NoticeInt;
}
