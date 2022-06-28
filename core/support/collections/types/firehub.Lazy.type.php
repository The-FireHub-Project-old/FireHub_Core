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
use Generator, Closure, Traversable, Error;

use function iterator_to_array;
use function iterator_count;
use function serialize;
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

    /**
     * @inheritDoc
     */
    public function __construct (private Closure $callable) {}

    /**
     * {@inheritDoc}
     *
     * @return array<int|string, mixed> Array from collection.
     */
    public function all ():array {

        return iterator_to_array($this->invokeItems());

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
    public function filter (Closure $callback):self {

        // return new collection
        return new self(function () use ($callback):Generator {

            // iterate over current items
            foreach ($this->items as $key => $value) {

                // add items to array if callback is true
                !$callback($key, $value) ?: yield $key => $value;

            }

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

        });

    }

    /**
     * @inheritDoc
     */
    public function serialize ():string {

        return serialize($this);

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

        return $this->all();

    }

    /**
     * {@inheritDoc}
     *
     * @return array<int|string, mixed> Array from collection.
     */
    public function __serialize ():array {

        return $this->all();

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