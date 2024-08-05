<?php
/**
 * Add custom capabilities to roles.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Constants\CAP;
use DRPSermonManager\Interfaces\RolesInt;

/**
 * Add custom capabilities to roles.
 */
class Roles implements RolesInt {

	/**
	 * Register callbacks.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register(): void {
		add_action( 'drpsermon_after_post_setup', array( $this, 'add' ) );
	}

	/**
	 * Add capablilities to roles.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function add(): void {
		$role_list = array( 'administrator', 'editor', 'author' );

		foreach ( $role_list as $role_name ) {
			$role = get_role( $role_name );
			if ( null === $role || ! ( $role instanceof \WP_Role ) ) {
				// @codeCoverageIgnoreStart
				continue;
				// @codeCoverageIgnoreEnd
			}

			// Read sermons.
			$role->add_cap( CAP::READ_SERMON );
			$role->add_cap( CAP::READ_PRIVATE_SERMONS );

			// Edit sermons.
			$role->add_cap( CAP::EDIT_SERMON );
			$role->add_cap( CAP::EDIT_SERMONS );
			$role->add_cap( CAP::EDIT_PRIVATE_SERMONS );
			$role->add_cap( CAP::EDIT_PUBLISHED_SERMONS );

			// Delete sermons.
			$role->add_cap( CAP::DELETE_SERMON );
			$role->add_cap( CAP::DELETE_SERMONS );
			$role->add_cap( CAP::DELETE_PUBLISHED_SERMONS );
			$role->add_cap( CAP::DELETE_PRIVATE_SERMONS );

			// Publish sermons.
			$role->add_cap( CAP::PUBLISH_SERMONS );

			// Manage categories & tags.
			$role->add_cap( CAP::MANAGE_CATAGORIES );

			// Add additional roles for administrator.
			if ( 'administrator' === $role_name ) {
				// Access to Sermon Manager Settings.
				$role->add_cap( CAP::MANAGE_SETTINGS );
			}

			// Add additional roles for administrator and editor.
			if ( 'author' !== $role_name ) {
				$role->add_cap( CAP::EDIT_OTHERS_SERMONS );
				$role->add_cap( CAP::DELETE_OTHERS_SERMONS );
			}
		}
	}

	/**
	 * Remove capabilities from roles.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function remove(): void {
		$role_list = array( 'administrator', 'editor', 'author' );

		foreach ( $role_list as $role_name ) {
			$role = get_role( $role_name );
			if ( null === $role || ! ( $role instanceof \WP_Role ) ) {
				// @codeCoverageIgnoreStart
				continue;
				// @codeCoverageIgnoreEnd
			}

			// Read sermons.
			$role->remove_cap( CAP::READ_SERMON );
			$role->remove_cap( CAP::READ_PRIVATE_SERMONS );

			// Edit sermons.
			$role->remove_cap( CAP::EDIT_SERMON );
			$role->remove_cap( CAP::EDIT_SERMONS );
			$role->remove_cap( CAP::EDIT_PRIVATE_SERMONS );
			$role->remove_cap( CAP::DELETE_PUBLISHED_SERMONS );

			// Delete sermons.
			$role->remove_cap( CAP::DELETE_SERMON );
			$role->remove_cap( CAP::DELETE_SERMONS );
			$role->remove_cap( CAP::DELETE_PUBLISHED_SERMONS );
			$role->remove_cap( CAP::DELETE_PRIVATE_SERMONS );

			// Publish sermons.
			$role->remove_cap( CAP::PUBLISH_SERMONS );

			// Manage categories & tags.
			$role->remove_cap( CAP::MANAGE_CATAGORIES );

			// Add additional roles for administrator.
			if ( 'administrator' === $role_name ) {
				// Access to Sermon Manager Settings.
				$role->remove_cap( CAP::MANAGE_SETTINGS );
			}

			// Add additional roles for administrator and editor.
			if ( 'author' !== $role_name ) {
				$role->remove_cap( CAP::EDIT_OTHERS_SERMONS );
				$role->remove_cap( CAP::DELETE_OTHERS_SERMONS );
			}
		}
	}
}
