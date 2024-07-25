<?php

namespace DRPSermonManager\Core\Traits;

/**
 * Singleton trait.
 */
trait SingletonTrait
{
    protected static $instance;

    final public static function getInstance()
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
