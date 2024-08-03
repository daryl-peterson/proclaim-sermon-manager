<?php

namespace DRPSermonManager\Traits;

/**
 * Singleton trait.
 */
trait SingletonTrait {

	protected static $instance;

	/**
	 * Get object instance
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
