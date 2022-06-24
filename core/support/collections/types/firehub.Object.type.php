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
use function serialize;
use function json_encode;
use function is_object;
use function sprintf;

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
     * @return SplObjectStorage<self, object> Current array.
     */
    public function &__get (string $name):SplObjectStorage {

        // check if property name is "items"
        if ($name !== 'items') {

            throw new Error(sprintf('Property %s doesn\'t exist.', $name));

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
     * @return void
     */
    public function __set (string $name, SplObjectStorage $value):void {

        // check if property name is "items", if is - set to value
        $name === 'items' ? $this->$name = $value : throw new Error(sprintf('Property %s doesn\'t exist.', $name));

    }

}