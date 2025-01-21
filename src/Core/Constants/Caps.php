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

	public const PT          = DRPPSM_PT_SERMON;
	public const ROLE_ADMIN  = 'administrator';
	public const ROLE_EDITOR = 'editor';
	public const ROLE_AUTHOR = 'author';

	public const ROLES = array(
		self::ROLE_ADMIN,
		self::ROLE_EDITOR,
		self::ROLE_AUTHOR,
	);

	// Singular.
	public const READ_SERMON   = 'read_' . self::PT;
	public const EDIT_SERMON   = 'edit_' . self::PT;
	public const DELETE_SERMON = 'delete_' . self::PT;

	// Plural.
	public const READ_PRIVATE_SERMONS     = 'read_private_' . self::PT . 's';
	public const EDIT_SERMONS             = 'edit_' . self::PT . 's';
	public const EDIT_PRIVATE_SERMONS     = 'edit_private_' . self::PT . 's';
	public const EDIT_PUBLISHED_SERMONS   = 'edit_published_' . self::PT . 's';
	public const EDIT_OTHERS_SERMONS      = 'edit_others_' . self::PT . 's';
	public const DELETE_SERMONS           = 'delete_' . self::PT . 's';
	public const DELETE_PUBLISHED_SERMONS = 'delete_published_' . self::PT . 's';
	public const DELETE_PRIVATE_SERMONS   = 'delete_private_' . self::PT . 's';
	public const DELETE_OTHERS_SERMONS    = 'delete_others_' . self::PT . 's';
	public const PUBLISH_SERMONS          = 'publish_' . self::PT . 's';

	public const MANAGE_CATAGORIES = 'manage_categories_' . self::PT;
	public const MANAGE_SETTINGS   = 'manage_settings_' . self::PT;

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

	public const PT_SERMON = array(
		self::MANAGE_SETTINGS   => self::MANAGE_SETTINGS,
		self::MANAGE_CATAGORIES => self::MANAGE_CATAGORIES,
	);

	public const PRIVILEGES = array(
		self::MANAGE_SETTINGS       => array( self::ROLE_ADMIN ),
		self::EDIT_OTHERS_SERMONS   => array( self::ROLE_ADMIN, self::ROLE_EDITOR ),
		self::DELETE_OTHERS_SERMONS => array( self::ROLE_ADMIN, self::ROLE_EDITOR ),
	);
}
