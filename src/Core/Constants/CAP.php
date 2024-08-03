<?php
/**
 * Capability constants.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Constants;

/**
 * Capability constants.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class CAP {

	// Read sermons.
	public const READ_SERMON          = 'read_drpsermon';
	public const READ_PRIVATE_SERMONS = 'read_private_drpsermons';

	// Edit sermons.
	public const EDIT_SERMON            = 'edit_drpsermon';
	public const EDIT_SERMONS           = 'edit_drpsermons';
	public const EDIT_PRIVATE_SERMONS   = 'edit_private_drpsermons';
	public const EDIT_PUBLISHED_SERMONS = 'edit_published_drpsermons';
	public const EDIT_OTHERS_SERMONS    = 'edit_others_drpsermons';

	// Delete sermons.
	public const DELETE_SERMON            = 'delete_drpsermon';
	public const DELETE_SERMONS           = 'delete_drpsermons';
	public const DELETE_PUBLISHED_SERMONS = 'delete_published_drpsermons';
	public const DELETE_PRIVATE_SERMONS   = 'delete_private_drpsermons';
	public const DELETE_OTHERS_SERMONS    = 'delete_others_drpsermons';

	// Publish.
	public const PUBLISH_SERMONS = 'publish_drpsermons';

	// Manage categories & tags.
	public const MANAGE_CATAGORIES = 'manage_drpsermon_categories';

	// Administrator.
	public const MANAGE_SETTINGS = 'manage_drpsermon_settings';

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
}
