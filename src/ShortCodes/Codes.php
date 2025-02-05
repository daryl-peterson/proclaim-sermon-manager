<?php
/**
 * Short codes list
 *
 * @package     DRPPSM\ShortCodes\Codes
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\ShortCodes;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Short codes list
 *
 * @package     DRPPSM\ShortCodes\Codes
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Codes implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Register short codes.
	 *
	 * @return bool|null
	 */
	public function register(): ?bool {
		Books::exec();
		Preachers::exec();
		SermonArchive::exec();
		Series::exec();
		SeriesLatest::exec();
		Sorting::exec();
		Topics::exec();
		return true;
	}
}
