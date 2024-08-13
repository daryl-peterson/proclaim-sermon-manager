<?php
/**
 * Settings constants.
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

/**
 * Settings constants.
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Settings {
	public const FIELD_MENU_ICON   = 'menu_icon';
	public const DEFAULT_MENU_ICON = 'dashicons-drppsm-holy-spirit';

	public const FIELD_DATE_FORMAT   = 'date_format';
	public const DEFAULT_DATE_FORMAT = 'mm/dd/YY';

	public const FIELD_SERMON_COUNT   = 'sermon_count';
	public const DEFAULT_SERMON_COUNT = 10;

	public const FIELD_ARCHIVE_SLUG   = 'archive_slug';
	public const DEFAULT_ARCHIVE_SLUG = 'sermons';

	public const FIELD_COMMON_BASE_SLUG   = 'common_base_slug';
	public const DEFAULT_COMMON_BASE_SLUG = false;

	public const FIELD_PREACHER_LABEL   = 'preacher_label';
	public const DEFAULT_PREACHER_LABEL = 'Preacher';


	public const FIELD_SERVICE_TYPE_LABEL   = 'service_type_label';
	public const DEFAULT_SERVICE_TYPE_LABEL = 'Service Type';
}
