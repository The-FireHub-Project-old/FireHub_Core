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
use SplFixedArray, Closure, Traversable, Error;

use function iterator_to_array;
use function serialize;
use function json_encode;
use function count;
use function sprintf;

/**
 * ### Index collection type
 *
 * Index Collection allows only integers as keys, but it is faster
 * and uses less memory than basic collection.
 * This collection type must be resized manually and allows only
 * integers within the range as indexes.
 * @since 0.2.0.pre-alpha.M2
 *
 * @property SplFixedArray $items Collection items
 *
 * @package FireHub\Support\Collections
 */
final class Index_Type implements CollectableRewindable {

    /**
     * {@inheritDoc}
     *
     * @param int $size <p>
     * Size argument lets you change the size of an array to the new size of size. If size is less than the current array size,
     * any values after the new size will be discarded. If size is greater than the current array size,
     * the array will be padded with null values.
     * </p>
     */
    public function __construct (private Closure $callable, private int $size = 0) {}

    /**
     * {@inheritDoc}
     *
     * @return array<int, mixed> Array from collection.
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
     * ### Change the size of an array
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int $size <p>
     * Size argument lets you change the size of an array to the new size. If size is less than the current array size,
     * any values after the new size will be discarded. If size is greater than the current array size,
     * the array will be padded with null values.
     * </p>
     *
     * @return bool Is the size set correctly.
     */
    public function setSize (int $size):bool {

        // change the size of index array
        return $this->items->setSize($size);

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
     * @return array<int, mixed> Array from collection.
     */
    public function jsonSerialize():array {

        return $this->items->jsonSerialize();

    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, mixed> Array from collection.
     */
    public function __serialize ():array {

        return $this->all();

    }

    /**
     * @inheritDoc
     */
    public function __unserialize (array $data):void {

        // recalculate size
        $this->size = count($data);

        // recreate callable
        $this->callable = function () use ($data):void {

            $i = 0;

            foreach ($data as $value) {

                $this->items[$i++] = $value;

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
     * @return SplFixedArray<mixed> Current array.
     */
    public function &__get (string $name):SplFixedArray {

        // check if property name is "items"
        if ($name !== 'items') {

            throw new Error(sprintf('Property %s doesn\'t exist.', $name));

        }

        // then invoke callable that will call "__set" magic method
        $this->items = new SplFixedArray();
        $this->items->setSize($this->size);

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
     * @param SplFixedArray<mixed> $value <p>
     * Property value.
     * </p>
     *
     * @return void
     */
    public function __set (string $name, SplFixedArray $value):void {

        // check if property name is "items", if is - set to value
        $name === 'items' ? $this->$name = $value : throw new Error(sprintf('Property %s doesn\'t exist.', $name));

    }

}