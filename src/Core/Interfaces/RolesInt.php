<?php
/**
 * Interface description.
 *
 * @package     Proclain Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Interfaces;

/**
 * Interface description.
 *
 * @package     Proclain Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface RolesInt {

	/**
	 * Add custom capabilities to roles.
	 *
	 * @since 1.0.0
	 */
	public function add(): void;

	/**
	 * Remove custom capabilities from roles.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function remove(): void;

	/**
	 * Register callbacks.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register(): void;
}
