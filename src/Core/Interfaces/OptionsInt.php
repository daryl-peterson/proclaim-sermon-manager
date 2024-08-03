<?php
/**
 * Options interface.
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
 * Options interface.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface OptionsInt extends Initable {

	/**
	 * Get option.
	 *
	 * @param string $name          Option name.
	 * @param mixed  $default_value Default value to return if not found.
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public function get( string $name, mixed $default_value = null ): mixed;

	/**
	 * Set option.
	 *
	 * @param string $name Option name.
	 * @param mixed  $value Option value.
	 * @return boolean True if option was set.
	 *
	 * @since 1.0.0
	 */
	public function set( string $name, mixed $value ): bool;

	/**
	 * Delete option.
	 *
	 * @param string $name Option name.
	 * @return bool True on success, false on failure.
	 *
	 * @since 1.0.0
	 */
	public function delete( string $name ): bool;
}
