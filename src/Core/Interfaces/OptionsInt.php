<?php
/**
 * Options interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
<<<<<<< HEAD
 *
=======
>>>>>>> 822b76c (Refactoring)
 * @since       1.0.0
 */

namespace DRPPSM\Interfaces;

/**
 * Options interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
<<<<<<< HEAD
 *
 * @since       1.0.0
 */
interface OptionsInt extends Initable {
=======
 * @since       1.0.0
 */
interface OptionsInt extends Executable {
>>>>>>> 822b76c (Refactoring)

	/**
	 * Get option.
	 *
	 * @param string $name          Option name.
	 * @param mixed  $default_value Default value to return if not found.
	 * @return mixed
<<<<<<< HEAD
	 *
=======
>>>>>>> 822b76c (Refactoring)
	 * @since 1.0.0
	 */
	public function get( string $name, mixed $default_value = null ): mixed;

	/**
	 * Set option.
	 *
	 * @param string $name Option name.
	 * @param mixed  $value Option value.
	 * @return boolean True if option was set.
<<<<<<< HEAD
	 *
=======
>>>>>>> 822b76c (Refactoring)
	 * @since 1.0.0
	 */
	public function set( string $name, mixed $value ): bool;

	/**
	 * Delete option.
	 *
	 * @param string $name Option name.
	 * @return bool True on success, false on failure.
<<<<<<< HEAD
	 *
=======
>>>>>>> 822b76c (Refactoring)
	 * @since 1.0.0
	 */
	public function delete( string $name ): bool;
}
