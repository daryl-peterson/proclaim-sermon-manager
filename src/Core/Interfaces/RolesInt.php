<?php
/**
 * Interface description.
 *
 * @package     Sermon Manager
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
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface RolesInt extends Initable {

	/**
	 * Add custom capabilities to roles.
	 *
	 * @since 1.0.0
	 */
	public function add(): void;

	/**
	 * Remove custom capabilities from roles.
	 *
	 * @since 1.0.0
	 */
	public function remove(): void;
}
