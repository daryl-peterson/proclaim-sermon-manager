<?php
/**
 * Text Domain translation interface.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Interfaces;

/**
 * Text Domain translation interface.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface TextDomainInt extends Initable, Registrable {

	/**
	 * Switch to site language.
	 *
	 * @since 1.0
	 */
	public function switch_to_tite_locale(): void;

	/**
	 * Restore language to original.
	 *
	 * @since 1.0
	 */
	public function restore_locale(): void;
}
