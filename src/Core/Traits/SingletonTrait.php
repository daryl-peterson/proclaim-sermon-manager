<?php
/**
 * Singleton trait
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPSermonManager\Traits;

/**
 * Singleton trait
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
trait SingletonTrait {

	/**
	 * Object instance
	 *
	 * @var mixed
	 */
	protected static $instance;

	/**
	 * Get object instance.
	 *
	 * @return static
	 */
	final public static function get_instance(): static {
		if ( null === static::$instance ) {
			// @codeCoverageIgnoreStart
			static::$instance = new static();
			// @codeCoverageIgnoreEnd
		}

		return static::$instance;
	}

	// @codeCoverageIgnoreStart
}
