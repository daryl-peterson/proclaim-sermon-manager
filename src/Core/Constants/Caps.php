<?php
/**
 * Capability constants.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Constants;

/**
 * Capability constants.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Caps {


	public const ROLE_ADMIN  = 'administrator';
	public const ROLE_EDITOR = 'editor';
	public const ROLE_AUTHOR = 'author';

	public const ROLES = array(
		self::ROLE_ADMIN,
		self::ROLE_EDITOR,
		self::ROLE_AUTHOR,
	);

	// Singular.
	public const READ_SERMON   = 'read_' . PT::SERMON;
	public const EDIT_SERMON   = 'edit_' . PT::SERMON;
	public const DELETE_SERMON = 'delete_' . PT::SERMON;

	// Plural.
	public const READ_PRIVATE_SERMONS     = 'read_private_' . PT::SERMONS;
	public const EDIT_SERMONS             = 'edit_' . PT::SERMONS;
	public const EDIT_PRIVATE_SERMONS     = 'edit_private_' . PT::SERMONS;
	public const EDIT_PUBLISHED_SERMONS   = 'edit_published_' . PT::SERMONS;
	public const EDIT_OTHERS_SERMONS      = 'edit_others_' . PT::SERMONS;
	public const DELETE_SERMONS           = 'delete_' . PT::SERMONS;
	public const DELETE_PUBLISHED_SERMONS = 'delete_published_' . PT::SERMONS;
	public const DELETE_PRIVATE_SERMONS   = 'delete_private_' . PT::SERMONS;
	public const DELETE_OTHERS_SERMONS    = 'delete_others_' . PT::SERMONS;
	public const PUBLISH_SERMONS          = 'publish_' . PT::SERMONS;

	public const MANAGE_CATAGORIES = 'manage_categories_' . PT::SERMON;
	public const MANAGE_SETTINGS   = 'manage_settings_' . PT::SERMON;

	// List.
	public const LIST = array(
		self::READ_SERMON,
		self::READ_PRIVATE_SERMONS,
		self::EDIT_SERMON,
		self::EDIT_SERMONS,
		self::EDIT_PRIVATE_SERMONS,
		self::EDIT_PUBLISHED_SERMONS,
		self::EDIT_OTHERS_SERMONS,
		self::DELETE_SERMON,
		self::DELETE_SERMONS,
		self::DELETE_PUBLISHED_SERMONS,
		self::DELETE_PRIVATE_SERMONS,
		self::PUBLISH_SERMONS,
		self::MANAGE_CATAGORIES,
		self::MANAGE_SETTINGS,
	);

	public const PRIVILEGES = array(
		self::MANAGE_SETTINGS       => array( self::ROLE_ADMIN ),
		self::EDIT_OTHERS_SERMONS   => array( self::ROLE_ADMIN, self::ROLE_EDITOR ),
		self::DELETE_OTHERS_SERMONS => array( self::ROLE_ADMIN, self::ROLE_EDITOR ),
	);
}
