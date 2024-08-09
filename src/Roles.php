<?php
/**
 * Add custom capabilities to roles.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\Caps;
use DRPPSM\Interfaces\RolesInt;

/**
 * Add custom capabilities to roles.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Roles implements RolesInt {

	/**
	 * List of roles to update.
	 *
	 * @var array
	 */
	private array $role_list;

	/**
	 * List of capabilities.
	 *
	 * @var array
	 */
	private array $caps;

	/**
	 * List of special capabilities.
	 *
	 * @var array
	 */
	private array $privileges;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->role_list  = Caps::ROLES;
		$this->caps       = Caps::LIST;
		$this->privileges = Caps::PRIVILEGES;
	}

	/**
	 * Add capablilities to roles.
	 *
	 * @return array Roles / caps.
	 * @since 1.0.0
	 */
	public function add(): array {
		$status = array();
		foreach ( $this->role_list as $role_name ) {
			$role = get_role( $role_name );

			// @codeCoverageIgnoreStart
			if ( ! $this->is_valid_role( $role ) ) {
				continue;
			}
			// @codeCoverageIgnoreEnd

			$status[ $role_name ]['status'] = 'valid';

			foreach ( $this->caps as $capability ) {
				$role->remove_cap( $capability );
				if ( ! key_exists( $capability, $this->privileges ) ) {
					$role->add_cap( $capability );
					$status[ $role_name ]['cap'][] = $capability;
					continue;
				}

				if ( in_array( $role_name, $this->privileges[ $capability ], true ) ) {
					$role->add_cap( $capability );
					$status[ $role_name ]['cap'][] = $capability;
				}
			}
		}
		return $status;
	}

	/**
	 * Remove capabilities from roles.
	 *
	 * @return array Roles / caps.
	 * @since 1.0.0
	 */
	public function remove(): array {
		$status = array();
		foreach ( $this->role_list as $role_name ) {
			$role = get_role( $role_name );

			// @codeCoverageIgnoreStart
			if ( ! $this->is_valid_role( $role ) ) {
				continue;
			}
			// @codeCoverageIgnoreEnd

			$status[ $role_name ]['status'] = 'valid';

			foreach ( $this->caps as $capability ) {
				$role->remove_cap( $capability );
				$status[ $role_name ]['cap'][] = $capability;
			}
		}
		return $status;
	}

	/**
	 * Get list of roles and capabilities.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_role_caps(): array {
		$caps = array();

		foreach ( $this->role_list as $role_name ) {
			$role = get_role( $role_name );

			// @codeCoverageIgnoreStart
			if ( ! isset( $role ) ) {
				continue;
			}
			// @codeCoverageIgnoreEnd

			$caps[ $role_name ] = $role->capabilities;

		}
		return $caps;
	}

	/**
	 * Check if the role is valid.
	 *
	 * @param mixed $role Role to check.
	 * @return bool
	 * @since 1.0.0
	 */
	public function is_valid_role( mixed $role ): bool {
		if ( null === $role || ! ( $role instanceof \WP_Role ) ) {
			return false;
		}
		return true;
	}
}
