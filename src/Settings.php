<?php
/**
 * Settings constants.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\Tax;

/**
 * Settings constants.
 *
 * @package     Proclaim Sermon Manager
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

	public const FIELD_PREACHER   = Tax::PREACHER;
	public const DEFAULT_PREACHER = 'Preacher';


	public const FIELD_SERVICE_TYPE   = Tax::SERVICE_TYPE;
	public const DEFAULT_SERVICE_TYPE = 'Service Type';

	public const FIELD_COMMENTS   = 'comments';
	public const DEFAULT_COMMENTS = false;
}
