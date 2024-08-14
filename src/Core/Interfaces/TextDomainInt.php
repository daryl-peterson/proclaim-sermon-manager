<?php
/**
 * Text Domain translation interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Interfaces;

/**
 * Text Domain translation interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
interface TextDomainInt extends Initable, Registrable {

	/**
	 * Switch to site language.
	 *
	 * @return bool True on success, otherwise false.
	 * @since 1.0.0
	 */
	public function switch_to_site_locale(): bool;

	/**
	 * Restore language to original.
	 *
	 * @return bool True on success, otherwise false.
	 * @since 1.0.0
	 */
	public function restore_locale(): bool;
}
