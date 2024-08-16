<?php
/**
 * Interface description.
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Interfaces;

/**
 * Interface description.
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
interface RolesInt extends Executable, Registrable {

	/**
	 * Add custom capabilities to roles.
	 *
	 * @return array List of roles / caps.
	 * @since 1.0.0
	 */
	public function add(): array;

	/**
	 * Remove custom capabilities from roles.
	 *
	 * @return array List of roles / caps.
	 * @since 1.0.0
	 */
	public function remove(): array;


	/**
	 * Get list of roles and capabilities.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_role_caps(): array;

	/**
	 * Check if the role is valid.
	 *
	 * @param mixed $role Role to check.
	 * @return bool
	 * @since 1.0.0
	 */
	public function is_valid_role( mixed $role ): bool;
}
