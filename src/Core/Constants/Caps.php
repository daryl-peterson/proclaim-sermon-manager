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

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd


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

	// Read sermons.
	public const READ_SERMON          = 'drppsm_read';
	public const READ_PRIVATE_SERMONS = 'drppsm_read_private';

	// Edit sermons.
	public const EDIT_SERMON            = 'drppsm_edit';
	public const EDIT_SERMONS           = 'drppsm_edits';
	public const EDIT_PRIVATE_SERMONS   = 'drppsm_edit_private';
	public const EDIT_PUBLISHED_SERMONS = 'drppsm_edit_published';
	public const EDIT_OTHERS_SERMONS    = 'drppsm_edit_others';

	// Delete sermons.
	public const DELETE_SERMON            = 'drppsm_delete';
	public const DELETE_SERMONS           = 'drppsm_deletes';
	public const DELETE_PUBLISHED_SERMONS = 'drppsm_delete_published';
	public const DELETE_PRIVATE_SERMONS   = 'drppsm_delete_private';
	public const DELETE_OTHERS_SERMONS    = 'drppsm_delete_others';

	// Publish.
	public const PUBLISH_SERMONS = 'drppsm_publish';

	// Manage categories & tags.
	public const MANAGE_CATAGORIES = 'drppsm_manage_categories';

	// Administrator.
	public const MANAGE_SETTINGS = 'drppsm_manage_settings';

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
