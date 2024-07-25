<?php

namespace DRPSermonManager;

use DRPSermonManager\Core\Exceptions\VimeoException;

/**
 * Collection of items.
 *
 * @author Daryl Peterson
 */
class Collection implements \IteratorAggregate
{
    protected array $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Get all items from the collection.
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Check if key exists.
     */
    public function has(mixed $key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * Add item to collection.
     */
    public function put(mixed $key, mixed $value): void
    {
        if (!isset($key)) {
            $this->push($value);
        }
        $this->items[$key] = $value;
    }

    /**
     * Add item to end of collection.
     */
    public function push(mixed $value): void
    {
        $this->items[] = $value;
    }

    /**
     * Get item from collection.
     * - If not found return default.
     */
    public function get(mixed $key, $default = null): mixed
    {
        if ($this->has($key)) {
            return $this->items[$key];
        }

        return $default;
    }

    /**
     * Magic method to get item from collection.
     * - $collection->some_key.
     */
    public function __get(mixed $key): mixed
    {
        return $this->get($key);
    }

    /**
     * Get count of items in the collection.
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Limit array to max number of items.
     *
     * @return int Number of removed items
     */
    public function limit(int $length): int
    {
        $count = $this->count();

        if (0 < $length) {
            $this->items = array_slice($this->items, 0, $length);
        }

        return $count - $this->count();
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Check if variable is a collection.
     * - exception=true will throw exception if variable is not a collection.
     * - exception=false will return bool.
     */
    public static function isCollection(mixed $collection, bool $exception = false): bool
    {
        if (!$collection instanceof Collection) {
            if ($exception) {
                throw new VimeoException('Unable to locate requested item');
            }

            return false;
        }

        return true;
    }
}
