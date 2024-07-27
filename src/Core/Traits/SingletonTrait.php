<?php

namespace DRPSermonManager\Traits;

/**
 * Singleton trait.
 */
trait SingletonTrait
{
    protected static $instance;

    final public static function getInstance(): static
    {
        if (null === static::$instance) {
            // @codeCoverageIgnoreStart
            static::$instance = new static();
            // @codeCoverageIgnoreEnd
        }

        return static::$instance;
    }

    // @codeCoverageIgnoreStart
    protected function __clone()
    {
    }

    public function __wakeup()
    {
    }
    // @codeCoverageIgnoreEnd
}
