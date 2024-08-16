<?php
/**
 * Requirements check interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Interfaces;

/**
 * Requirements check interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
interface RequirementsInt extends BaseInt, Registrable {

	/**
	 * Check PHP version
	 *
	 * @param string $version Required PHP version.
	 * @return void
	 * @throws PluginException Throws exception if requirements are not met.
	 */
	public function check_php_ver( string $version = '' ): void;

	/**
	 * Check WordPress verson
	 *
	 * @param string $version Required WordPress version.
	 * @return void
	 *
	 * @throws PluginException Throws exception if requirements are not met.
	 * @since 1.0.0
	 */
	public function check_wp_ver( string $version = '' ): void;
}
