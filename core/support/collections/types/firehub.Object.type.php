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

use FireHub\Support\Collections\CollectableRewindable;
use SplObjectStorage, Closure, Traversable, Error;

use function iterator_to_array;
use function is_object;
use function sprintf;
use function serialize;
use function json_encode;

/**
 * ### Object collection type
 *
 * Object collection allow you to work with large objects maps.
 * @since 0.2.0.pre-alpha.M2
 *
 * @property SplObjectStorage $items Collection items
 *
 * @package FireHub\Support\Collections
 */
final class Object_Type implements CollectableRewindable {

    /**
     * @inheritDoc
     */
    public function __construct (private Closure $callable) {}

    /**
     * {@inheritDon}
     *
     * @return array<int, object> Array from collection.
     */
    public function all ():array {

        return iterator_to_array($this->items);

    }

    /**
     * @inheritDoc
     */
    public function count ():int {

        return $this->items->count();

    }

    /**
     * ### Adds an item at the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param object $key <p>
     * Collection item key.
     * </p>
     * @param mixed $info <p>
     * The data to associate with the object.
     * </p>
     *
     * @throws Error If $offset is not object.
     *
     * @return void
     */
    public function add (object $key, mixed $info):void {

        !$this->offsetExists($key) ? $this->offsetSet($key, $info) : throw new Error(sprintf('Key %s already exist.', $key::class));

    }

    /**
     * {@inheritDoc}
     *
     * @param object $key <p>
     * Collection item key.
     * </p>
     *
     * @throws Error If $offset does not exist in Collection or is not object.
     */
    public function get (mixed $key):mixed {

        return $this->offsetGet($key);

    }

    /**
     * {@inheritDoc}
     *
     * @param object $key <p>
     * Collection item key.
     * </p>
     * @param mixed $value <p>
     * Collection item value.
     * </p>
     *
     * @throws Error If $offset is not object.
     */
    public function set (mixed $key, mixed $value):void {

        $this->offsetSet($key, $value);

    }

    /**
     * {@inheritDoc}
     *
     * @param object $key <p>
     * Collection item key.
     * </p>
     *
     * @throws Error If $offset is not object.
     */
    public function isset (mixed $key):bool {

        return $this->offsetExists($key);

    }

    /**
     * {@inheritDoc}
     *
     * @param object $key <p>
     * Collection item key.
     * </p>
     *
     * @throws Error If $offset is not object.
     */
    public function unset (mixed $key):void {

        $this->offsetUnset($key);

    }

    /**
     * @inheritDoc
     */
    public function each (Closure $callback):self {

        foreach ($this->items as $key => $value) {

            // run callable and break if result is false
            if ($callback($key, $value) === false) {

                break;

            }

        }

        return $this;

    }

    /**
     * @inheritDoc
     */
    public function walk (Closure $callback):self {

        // iterate over current items
        foreach ($this->items as $info => $object) {

            // add current callback value to same key
            $this->items[$object] = $callback($object, $info);

        }

        return $this;

    }

    /**
     * @inheritDoc
     */
    public function map (Closure $callback):self {

        // return new collection
        return new self(function ($items) use ($callback):void {

            // iterate over current items
            foreach ($this->items as $info => $object) {

                // add current callback value to same key
                $items[$object] = $callback($object, $info);

            }

        });

    }

    /**
     * @inheritDoc
     */
    public function chunk (int $size, Closure $callback):void {

        // create empty collection
        $collection = new self(function () {return [];});

        // iterate over current items
        foreach ($this->items as $key => $value) {

            // add item to collection
            $collection->items[$value] = $key;

            // see if we have the right amount in the batch
            if ($collection->count() === $size) {

                // pass the batch into the callback
                $callback($collection);

                // reset the collection
                $collection = new self(function () {return [];});

            }

        }

        // see if we have any leftover items to process
        if ($collection->count()) {

            // pass the collection into the callback
            $callback($collection);

        }

    }

    /**
     * @inheritDoc
     */
    public function merge (Closure $callback):self {

        $callback($this->items);

        return $this;

    }

    /**
     * @inheritDoc
     */
    public function filter (Closure $callback):self {

        // return new collection
        return new self(function ($items) use ($callback):void {

            // iterate over current items
            foreach ($this->items as $key => $value) {

                // add items to array if callback is true
                !$callback($key, $value) ?: $items[$value] = $key;

            }

        });

    }

    /**
     * {@inheritDoc}
     *
     * @param object $offset <p>
     * An offset to check for.
     * </p>
     *
     * @throws Error If $offset is not object.
     */
    public function offsetExists (mixed $offset):bool {

        return !is_object($offset) ? throw new Error('Key needs to be object.') : $this->items->contains($offset);

    }

    /**
     * {@inheritDoc}
     *
     * @param object $offset <p>
     * The offset to retrieve.
     * </p>
     *
     * @throws Error If $offset is not object or does not exist in Collection.
     */
    public function offsetGet (mixed $offset):mixed {

        return $this->offsetExists($offset) ? $this->items->offsetGet($offset) : throw new Error(sprintf('Key %s does not exist in Collection.', $offset::class));

    }

    /**
     * {@inheritDoc}
     *
     * @param object $offset <p>
     * The offset to assign the value to.
     * </p>
     *
     * @throws Error If $offset is not object.
     */
    public function offsetSet (mixed $offset, mixed $value):void {

        !is_object($offset) ? throw new Error('Key needs to be object.') : $this->items->attach($offset, $value);

    }

    /**
     * {@inheritDoc}
     *
     * @param object $offset <p>
     * The offset to assign the value to.
     * </p>
     *
     * @throws Error If $offset is not object.
     */
    public function offsetUnset (mixed $offset):void {;

        !$this->offsetExists($offset) ?: $this->items->detach($offset);

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
     * @return array<int, object> Array from collection.
     */
    public function jsonSerialize():array {

        return $this->all();

    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, object> Array from collection.
     */
    public function __serialize ():array {

        return $this->all();

    }

    /**
     * @inheritDoc
     */
    public function __unserialize (array $data):void {

        // recreate callable
        $this->callable = function () use ($data):void {

            foreach ($data as $key => $value) {

                // check if value is object
                if (is_object($value)) {

                    $this->items[$value] = $key;

                }

            }

        };

    }

    /**
     * @inheritDoc
     */
    public function getIterator ():Traversable {

        yield from $this->items;

    }

    /**
     * ### Populate current items
     * @since 0.2.0.pre-alpha.M2
     *
     * @return void
     */
    private function invokeItems ():void {

        ($this->callable)($this->items);

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
     * @return SplObjectStorage<self, object> Current array.
     */
    public function &__get (string $name):SplObjectStorage {

        // check if property name is "items"
        if ($name !== 'items') {

            throw new Error(sprintf('Property %s does not exist.', $name));

        }

        // then invoke callable that will call "__set" magic method
        $this->items = new SplObjectStorage();

        // invoke callable
        $this->invokeItems();

        return $this->items;

    }

    /**
     * ### Set property name
     * @since 0.2.0.pre-alpha.M2
     *
     * @param string $name <p>
     * Property name.
     * </p>
     * @param SplObjectStorage<self, object> $value <p>
     * Property value.
     * </p>
     *
     * @throws Error If property does not exist.
     *
     * @return void
     */
    public function __set (string $name, SplObjectStorage $value):void {

        // check if property name is "items", if is - set to value
        $name === 'items' ? $this->$name = $value : throw new Error(sprintf('Property %s does not exist.', $name));

    }

}