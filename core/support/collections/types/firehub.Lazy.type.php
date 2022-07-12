<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package.
 * @since 0.2.0.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license OSL Open Source License version 3 - [https://opensource.org/licenses/OSL-3.0](https://opensource.org/licenses/OSL-3.0)
 *
 * @package FireHub\Support\Collections
 * @version 1.0
 */

namespace FireHub\Support\Collections\Types;

use FireHub\Support\Collections\CollectableNonRewindable;
use FireHub\Support\Traits\Tappable;
use Generator, Closure, Traversable, Error;

use function iterator_count;
use function in_array;
use function serialize;
use function iterator_to_array;
use function json_encode;
use function sprintf;

/**
 * ### Lazy collection type
 *
 * Lazy Collection allow you to work with very large datasets
 * while keeping memory usage low.
 * @since 0.2.0.pre-alpha.M2
 *
 * @property Generator $items Collection items
 *
 * @package FireHub\Support\Collections
 */
final class Lazy_Type implements CollectableNonRewindable {

    use Tappable;

    /**
     * @inheritDoc
     */
    public function __construct (private Closure $callable) {}

    /**
     * {@inheritDoc}
     *
     * @return Generator Items from collection.
     */
    public function all ():Generator {

        return $this->items;

    }

    /**
     * @inheritDoc
     */
    public function count ():int {

        return iterator_count($this->invokeItems());

    }

    /**
     * @inheritDoc
     */
    public function isEmpty ():bool {

        return $this->count() === 0;

    }

    /**
     * @inheritDoc
     */
    public function filter (Closure $callback):self {

        // return new collection
        return new self(function () use ($callback):Generator {

            // iterate over current items
            foreach ($this->items as $key => $value) {

                // add items to array if callback is true
                !$callback($key, $value) ?: yield $key => $value;

            }

            return [];

        });

    }

    /**
     * @inheritDoc
     */
    public function reject (Closure $callback):self {

        // return new collection
        return new self(function () use ($callback):Generator {

            // iterate over current items
            foreach ($this->items as $key => $value) {

                // add items to array if callback is false
                $callback($key, $value) ?: yield $key => $value;

            }

            return [];

        });

    }

    /**
     * {@inheritDoc}
     *
     * @param int|string ...$keys <p>
     * List of keys to return.
     * </p>
     */
    public function only (mixed ...$keys):self {

        // return new collection
        return new self(function () use ($keys):Generator {

            // iterate over current items
            foreach ($this->items as $key => $value) {

                // add items to array if callback is false
                !in_array($key, $keys) ?: yield $key => $value;

            }

            return [];

        });

    }

    /**
     * {@inheritDoc}
     *
     * @param int|string ...$keys <p>
     * List of keys to return.
     * </p>
     */
    public function except (mixed ...$keys):self {

        // return new collection
        return new self(function () use ($keys):Generator {

            // iterate over current items
            foreach ($this->items as $key => $value) {

                // add items to array if callback is false
                in_array($key, $keys) ?: yield $key => $value;

            }

            return [];

        });

    }

    /**
     * ### Remove number of elements from the beginning of the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int $offset <p>
     * Number of items to skip.
     * </p>
     *
     * @return self New skipped collection.
     */
    public function skip (int $offset):self {

        // return new collection
        return new self(function () use ($offset):Generator {

            // iterate over current items
            $counter = 0;
            foreach ($this->items as $key => $value) {

                if ($counter++ < $offset) continue;

                yield $key => $value;

            }

            return [];

        });

    }

    /**
     * ### Remove number of elements from the beginning of the collection until the given callback returns true
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return self New skipped collection.
     */
    public function skipUntil (Closure $callback):self {

        // return new collection
        return new self(function () use ($callback):Generator {

            // iterate over current items
            while ($this->items->valid() && !$callback($this->items->key(), $this->items->current())) {

                $this->items->next();

            }

            // iterate over other items
            while ($this->items->valid()) {

                yield $this->items->key() => $this->items->current();

                $this->items->next();

            }

            return [];

        });

    }

    /**
     * ### Remove number of elements from the beginning of the collection while the given callback returns true
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return self New skipped collection.
     */
    public function skipWhile (Closure $callback):self {

        // return new collection
        return new self(function () use ($callback):Generator {

            // iterate over current items
            while ($this->items->valid() && $callback($this->items->key(), $this->items->current())) {

                $this->items->next();

            }

            // iterate over other items
            while ($this->items->valid()) {

                yield $this->items->key() => $this->items->current();

                $this->items->next();

            }

            return [];

        });

    }

    /**
     * ### Return new collection with specified number of items
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int $limit <p>
     * Number of items to take.
     * </p>
     *
     * @return self New collection.
     */
    public function take (int $limit):self {

        // return new collection
        return new self(function () use ($limit):Generator {

            // iterate over current items
            $counter = 0;
            foreach ($this->items as $key => $value) {

                if ($counter++ >= $limit) continue;

                yield $key => $value;

            }

            return [];

        });

    }

    /**
     * ### Return new collection with specified number of items until the given callback returns true
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return self New collection.
     */
    public function takeUntil (Closure $callback):self {

        // return new collection
        return new self(function () use ($callback):Generator {

            // iterate over current items
            while ($this->items->valid() && !$callback($this->items->key(), $this->items->current())) {

                yield $this->items->key() => $this->items->current();

                $this->items->next();

            }

            return [];

        });

    }

    /**
     * ### Return new collection with specified number of items while the given callback returns true
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return self New collection.
     */
    public function takeWhile (Closure $callback):self {

        // return new collection
        return new self(function () use ($callback):Generator {

            // iterate over current items
            while ($this->items->valid() && $callback($this->items->key(), $this->items->current())) {

                yield $this->items->key() => $this->items->current();

                $this->items->next();

            }

            return [];

        });

    }

    /**
     * ### Return new collection with keys as values
     * @since 0.2.0.pre-alpha.M2
     *
     * @param mixed $filter [optional] <p>
     * If specified, then only keys containing these values are returned.
     * </p>
     *
     * @return self New collection from keys.
     */
    public function keys (mixed $filter = null):self {

        // return new collection
        return new self(function () use ($filter):Generator {

            // iterate over current items
            foreach ($this->items as $key => $value) {

                if (is_null($filter)) { // if filter is not set

                    yield $key;

                } else if ($value === $filter) { // if value is equal to filter

                    yield $key;

                }

            }

            return [];

        });

    }

    /**
     * ### Retrieve only values from collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return self New collection with values only.
     */
    public function values ():self {

        // return new collection
        return new self(function ():Generator {

            // iterate over current items
            foreach ($this->items as $value) {

                yield $value;

            }

            return [];

        });

    }

    /**
     * @inheritDoc
     */
    public function serialize ():string {

        return serialize($this);

    }

    /**
     * {@inheritDoc}
     *
     * @return array<int|string, mixed> Array from collection.
     */
    public function toArray ():array {

        return iterator_to_array($this->invokeItems());

    }

    /**
     * @inheritDoc
     */
    public function toJSON ():string|false {

        return json_encode($this);

    }

    /**
     * {@inheritDoc}
     *
     * @return array<int|string, mixed> Array from collection.
     */
    public function jsonSerialize():array {

        return $this->toArray();

    }

    /**
     * {@inheritDoc}
     *
     * @return array<int|string, mixed> Array from collection.
     */
    public function __serialize ():array {

        return $this->toArray();

    }

    /**
     * @inheritDoc
     */
    public function __unserialize (array $data):void {

        // recreate callable
        $this->callable = function () use ($data):Generator {

            foreach ($data as $key => $value) {

                yield $key => $value;

            }

        };

    }

    /**
     * @inheritDoc
     */
    public function getIterator ():Traversable {

        return $this->invokeItems();

    }

    /**
     * ### Populate current items
     * @since 0.2.0.pre-alpha.M2
     *
     * @return Generator Collection items.
     */
    private function invokeItems ():Generator {

        return ($this->callable)();

    }

    /**
     * ### Get property name
     * @since 0.2.0.pre-alpha.M2
     *
     * @param string $name <p>
     * Property name.
     * </p>
     *
     * @throws Error If property does not exist.
     *
     * @return Generator Current array.
     */
    public function &__get (string $name):Generator {

        // check if property name is "items", then invoke callable that will call "__set" magic method
        $this->items = $name === 'items' ? $this->invokeItems() : throw new Error(sprintf('Property %s does not exist.', $name));

        return $this->items;

    }

    /**
     * ### Set property name
     * @since 0.2.0.pre-alpha.M2
     *
     * @param string $name <p>
     * Property name.
     * </p>
     * @param Generator $value <p>
     * Property value.
     * </p>
     *
     * @throws Error If property does not exist.
     *
     * @return void
     */
    public function __set (string $name, Generator $value):void {

        // check if property name is "items", if is - set to value
        $name === 'items' ? $this->$name = $value : throw new Error(sprintf('Property %s does not exist.', $name));

    }

}