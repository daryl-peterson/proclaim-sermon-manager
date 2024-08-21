<?php
/**
 * Rewrite interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Interfaces;

/**
 * Rewrite interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
interface RewriteInt extends Executable, Registrable {


	/**
	 * A plugin has been activated/deactivated force check.
	 *
	 * @param string  $plugin Plugin name.
	 * @param boolean $network_wide Network flag.
	 * @return void
	 * @since 1.0.0
	 */
	public function reset( string $plugin, bool $network_wide );

	/**
	 * Check if any conflicts exist.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function find_conflicts();
}
