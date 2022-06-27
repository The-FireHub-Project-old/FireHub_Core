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
use Closure, Traversable, Error;

use function count;
use function array_shift;
use function array_unshift;
use function array_pop;
use function array_push;
use function is_string;
use function is_int;
use function sprintf;
use function serialize;
use function json_encode;

/**
 * ### Basic collection type
 *
 * Basic Collection type is collection that has main focus of performance
 * and doesn't concern itself about memory consumption.
 * This collection can hold any type of data.
 * @since 0.2.0.pre-alpha.M2
 *
 * @property array<int|string, mixed> $items Collection items
 *
 * @package FireHub\Support\Collections
 */
final class Array_Type implements CollectableRewindable {

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

        return $this->items;

    }

    /**
     * @inheritDoc
     */
    public function count ():int {

        return count($this->items);

    }

    /**
     * ### Adds an item at the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param string|int $key <p>
     * Collection item key.
     * </p>
     * @param mixed $value <p>
     * Collection item value.
     * </p>
     *
     * @throws Error If $offset is not int or string or key already exist.
     *
     * @return void
     */
    public function add (string|int $key, mixed $value):void {

        !$this->offsetExists($key) ? $this->offsetSet($key, $value) : throw new Error(sprintf('Key %s already exist.', $key));

    }

    /**
     * {@inheritDoc}
     *
     * @param string|int $key <p>
     * Collection item key.
     * </p>
     *
     * @throws Error If $offset does not exist in Collection or is not int or string.
     */
    public function get (mixed $key):mixed {

        return $this->offsetGet($key);

    }

    /**
     * {@inheritDoc}
     *
     * @param string|int $key <p>
     * Collection item key.
     * </p>
     * @param mixed $value <p>
     * Collection item value.
     * </p>
     *
     * @throws Error If $offset is not int or string.
     */
    public function set (mixed $key, mixed $value):void {

        $this->offsetSet($key, $value);

    }

    /**
     * {@inheritDoc}
     *
     * @param string|int $key <p>
     * Collection item key.
     * </p>
     *
     * @throws Error If $offset is not int or string.
     */
    public function isset (mixed $key):bool {

        return $this->offsetExists($key);

    }

    /**
     * {@inheritDoc}
     *
     * @param string|int $key <p>
     * Collection item key.
     * </p>
     *
     * @throws Error If $offset is not int or string.
     */
    public function unset (mixed $key):void {

        $this->offsetUnset($key);

    }

    /**
     * ### Removes an item at the beginning of the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return void
     */
    public function shift ():void {

        array_shift($this->items);

    }

    /**
     * ### Push items at the beginning of the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param mixed ...$values <p>
     * List of values to unshift.
     * </p>
     *
     * @return void
     */
    public function unshift (mixed ...$values):void {

        array_unshift($this->items, ...$values);

    }

    /**
     * ### Removes an item at the end of the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return void
     */
    public function pop ():void {

        array_pop($this->items);

    }

    /**
     * ### Push items at the end of the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param mixed ...$values <p>
     * List of values to push.
     * </p>
     *
     * @return void
     */
    public function push (mixed ...$values):void {

        array_push($this->items, ...$values);

    }

    /**
     * {@inheritDoc}
     *
     * @throws Error If $offset is not int or string.
     */
    public function offsetExists (mixed $offset):bool {

        return is_string($offset) || is_int($offset) ? isset($this->items[$offset]) : throw new Error('Key needs to be int or string.');

    }

    /**
     * {@inheritDoc}
     *
     * @throws Error If $offset does not exist in Collection or is not int or string.
     */
    public function offsetGet (mixed $offset):mixed {

        return $this->offsetExists($offset) ? $this->items[$offset] : throw new Error('Key does not exist.');

    }

    /**
     * {@inheritDoc}
     *
     * @throws Error If $offset is not int or string.
     */
    public function offsetSet (mixed $offset, mixed $value):void {

        is_string($offset) || is_int($offset)
            ? empty($offset)
                ? $this->items[] = $value
                : $this->items[$offset] = $value
            : throw new Error('Key needs to be int or string.');

    }

    /**
     * {@inheritDoc}
     *
     * @throws Error If $offset is not int or string.
     */
    public function offsetUnset (mixed $offset):void {

        if ($this->offsetExists($offset)) {

            unset($this->items[$offset]);

        }

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

        return $this->items;

    }

    /**
     * {@inheritDoc}
     *
     * @return array<int|string, mixed> Array from collection.
     */
    public function __serialize ():array {

        return $this->items;

    }

    /**
     * @inheritDoc
     */
    public function __unserialize (array $data):void {

        // recreate callable
        $this->callable = function () use ($data):array {

            return $data;

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
     * @return array<int|string, mixed> $items Collection items.
     */
    private function invokeItems ():array {

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
     * @return array<int|string, mixed> Current array.
     */
    public function &__get (string $name):array {

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
     * @param array<int|string, mixed> $value <p>
     * Property value.
     * </p>
     *
     * @throws Error If property does not exist.
     *
     * @return void
     */
    public function __set (string $name, array $value):void {

        // check if property name is "items", if is - set to value
        $name === 'items' ? $this->$name = $value : throw new Error(sprintf('Property %s does not exist.', $name));

    }

}