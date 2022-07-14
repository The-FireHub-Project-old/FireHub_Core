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

use FireHub\Support\Collections\CollectableRewindable;;
use FireHub\Support\Traits\Tappable;
use FireHub\Support\LowLevel\Iterator;
use SplFixedArray, Closure, Traversable, Throwable, Error;

use function is_callable;
use function in_array;
use function sprintf;
use function range;
use function shuffle;
use function is_int;
use function serialize;
use function iterator_to_array;
use function json_encode;
use function count;

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

    use Tappable;

    /**
     * {@inheritDoc}
     *
     * @param Closure $callable <p>
     * Data from callable source.
     * </p>
     * @param int $size [optional] <p>
     * Size argument lets you change the size of an array to the new size of size. If size is less than the current array size,
     * any values after the new size will be discarded. If size is greater than the current array size,
     * the array will be padded with null values.
     * </p>
     */
    public function __construct (private Closure $callable, private int $size = 0) {}

    /**
     * {@inheritDoc}
     *
     * @return SplFixedArray<mixed> Items from collection.
     */
    public function all ():SplFixedArray {

        return $this->items;

    }

    /**
     * {@inheritDoc}
     *
     * @since 0.2.0.pre-alpha.M2
     * @since 0.2.1.pre-alpha.M2 Added low-level Iterator functions.
     */
    public function count ():int {

        return Iterator::count($this->items);

    }

    /**
     * {@inheritDoc}
     *
     * @since 0.2.0.pre-alpha.M2
     * @since 0.2.1.pre-alpha.M2 Added low-level Iterator functions.
     */
    public function isEmpty ():bool {

        return Iterator::isEmpty($this->items);

    }

    /**
     * ### Replaces an item at the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int $key <p>
     * Collection item key.
     * </p>
     * @param mixed $value <p>
     * Collection item value.
     * </p>
     *
     * @throws Error If $offset is not int or string or does not exist.
     */
    public function replace (int $key, mixed $value):void {

        $this->offsetExists($key) ? $this->offsetSet($key, $value) : throw new Error(sprintf('Key %s does not exist.', $key));

    }

    /**
     * {@inheritDoc}
     *
     * @param int $key <p>
     * Collection item key.
     * </p>
     *
     * @throws Error If $offset does not exist in Collection or is not int.
     */
    public function get (mixed $key):mixed {

        return $this->offsetGet($key);

    }

    /**
     * {@inheritDoc}
     *
     * @param int $key <p>
     * Collection item key.
     * </p>
     * @param mixed $value <p>
     * Collection item value.
     * </p>
     *
     * @throws Error If $offset is not int.
     */
    public function set (mixed $key, mixed $value):void {

        $this->offsetSet($key, $value);

    }

    /**
     * {@inheritDoc}
     *
     * @param int $key <p>
     * Collection item key.
     * </p>
     *
     * @throws Error If $offset is not int.
     */
    public function isset (mixed $key):bool {

        return $this->offsetExists($key);

    }

    /**
     * {@inheritDoc}
     *
     * @param int $key <p>
     * Collection item key.
     * </p>
     *
     * @throws Error If $offset is not int.
     */
    public function unset (mixed $key):void {

        $this->offsetUnset($key);

    }

    /**
     * ### Removes an item at the end of the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return void
     */
    public function pop ():void {

        $this->setSize($this->count() - 1);

    }

    /**
     * ### Push an item at the end of the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param mixed ...$values <p>
     * List of values to push.
     * </p>
     *
     * @return void
     */
    public function push (mixed ...$values):void {

        foreach ($values as $value) {

            $this->setSize($this->count() + 1);

            $this->items[$this->count() - 1] = $value;

        }

    }

    /**
     * @inheritDoc
     */
    public function each (Closure $callback):self {

        foreach ($this->items as $value) {

            // run callable and break if result is false
            if ($callback($value) === false) {

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
        foreach ($this->items as $key => $value) {

            // add current callback value to same key
            $this->items[$key] = $callback($key, $value);

        }

        return $this;

    }

    /**
     * @inheritDoc
     */
    public function map (Closure $callback):self {

        // return new collection
        return new self(function ($items) use ($callback):void {

            // change the size of array to be same as current one
            $items->setSize($this->items->getSize());

            // iterate over current items
            foreach ($this->items as $key => $value) {

                // add current callback value to same key
                $items[$key] = $callback($key, $value);

            }

        });

    }

    /**
     * @inheritDoc
     */
    public function chunk (int $size, Closure $callback):void {

        // create empty collection
        $collection = new self(function () {return [];});
        $collection->setSize($size);
        $count = 0;

        // iterate over current items
        foreach ($this->items as $value) {

            // add item to collection
            $collection->items[$count++] = $value;

            if ($count === $size) {

                // pass the batch into the callback
                $callback($collection);

                // reset the collection
                $collection = new self(function () {return [];});
                $collection->setSize($size);
                $count = 0;

            }

        }

        // see if we have any leftover items to process
        if ($count > 0) {

            // pass the collection into the callback
            $callback($collection);

        }

    }

    /**
     * {@inheritDoc}
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     * @param int $size [optional] <p>
     * Size argument lets you change the size of an array to the new size of size. If size is less than the current array size,
     * any values after the new size will be discarded. If size is greater than the current array size,
     * the array will be padded with null values.
     * </p>
     */
    public function merge (Closure $callback, int $size = 1):self {

        $this->items->setSize($this->items->getSize() + $size);

        $callback($this->items, $this->items->getSize() - $size);

        return $this;

    }

    /**
     * @inheritDoc
     */
    public function filter (Closure $callback):self {

        // return new collection
        return new self(function ($items) use ($callback):void {

            // change the size of array to be same as current one
            $items->setSize($this->items->getSize());

            // iterate over current items
            $counter = 0;
            foreach ($this->items as $key => $value) {

                // add items to array if callback is true
                !$callback($key, $value) ?: $items[$counter++] = $value;

            }

            // change the size of array to match filtered results
            $items->setSize($counter);

        });

    }

    /**
     * @inheritDoc
     */
    public function reject (Closure $callback):self {

        // return new collection
        return new self(function ($items) use ($callback):void {

            // change the size of array to be same as current one
            $items->setSize($this->items->getSize());

            // iterate over current items
            $counter = 0;
            foreach ($this->items as $key => $value) {

                // add items to array if callback is false
                $callback($key, $value) ?: $items[$counter++] = $value;

            }

            // change the size of array to match filtered results
            $items->setSize($counter);

        });

    }

    /**
     * @inheritDoc
     */
    public function contains (mixed $search):bool {

        if (is_callable($search)) { // $search is callable

            // iterate over current items
            foreach ($this->items as $value) {

                // if callback is true return early true
                if ($search($value)) {

                    return true;

                }

            }

        } else { // $search is not callable

            // iterate over current items
            foreach ($this->items as $value) {

                // if callback is true return early true
                if ($search === $value) {

                    return true;

                }

            }

        }

        // if no condition was meet, return false
        return false;

    }

    /**
     * @inheritDoc
     */
    public function every (Closure $callback):bool {

        // iterate over current items
        foreach ($this->items as $key => $value) {

            // if callback is true return early trues
            if (!$callback($key, $value)) {

                return false;

            }

        }

        return true;

    }

    /**
     * {@inheritDoc}
     *
     * @param int ...$keys <p>
     * List of keys to return.
     * </p>
     */
    public function only (mixed ...$keys):self {

        // return new collection
        return new self(function ($items) use ($keys):void {

            // change the size of array to be same as current one
            $items->setSize($this->items->getSize());

            // iterate over current items
            $counter = 0;
            foreach ($this->items as $key => $value) {

                // add items to array if callback is false
                !in_array($key, $keys) ?: $items[$counter++] = $value;

            }

            // change the size of array to match filtered results
            $items->setSize($counter);

        });

    }

    /**
     * {@inheritDoc}
     *
     * @param int ...$keys <p>
     * List of keys to return.
     * </p>
     */
    public function except (mixed ...$keys):self {

        // return new collection
        return new self(function ($items) use ($keys):void {

            // change the size of array to be same as current one
            $items->setSize($this->items->getSize());

            // iterate over current items
            $counter = 0;
            foreach ($this->items as $key => $value) {

                // add items to array if callback is false
                in_array($key, $keys) ?: $items[$counter++] = $value;

            }

            // change the size of array to match filtered results
            $items->setSize($counter);

        });

    }

    /**
     * ### Pick one or more random values out of the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int $number [optional] <p>
     * Specifies how many entries you want to pick.
     * </p>
     *
     * @throws Error If asked number of items is greater than total number of items in collection.
     *
     * @return mixed If you are picking only one entry, returns the key for a random entry. Otherwise, it returns an array of keys for the random entries.
     */
    public function random (int $number = 1):mixed {

        // check if asked number of items is greater than total number of items in collection
        !($number > $this->count()) ?: throw new Error(sprintf('Asked random values are %d, and are greater then total number of items in collection %d.', $number, $this->count()));

        // set the valid range for possible keys
        $range = range(0, $this->count() - 1);

        // shuffle an array
        shuffle($range);

        // if asked number is 1, we will not return array of values
        if ($number === 1) {

            // return first random key, this is why we shuffled our key range
            return $this->items[$range[0]];

        }

        // fill keys based on our range and number of asked items
        for ($counter = 0; $counter < $number; $counter++) {

            $keys[$counter] = $range[$counter];

        }

        // iterate over keys and fill new array with matching records from out existing collection
        foreach ($keys ?? [] as $key) {

            $items[] = $this->items[$key];

        }

        return $items ?? [];

    }

    /**
     * {@inheritDoc}
     *
     * @return int|false The key for needle if it is found in the collection, false otherwise. If needle is found in haystack more than once, the first matching key is returned.
     */
    public function search (mixed $value):int|false {

        foreach ($this->items as $key => $val) {

            if ($val === $value) {

                return $key;

            }

        }
        return false;


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
        return new self(function ($items) use ($offset):void {

            // change the size of array to be same as current one
            $items->setSize($this->items->getSize());

            // iterate over current items
            $counter = 0;
            foreach ($this->items as $key => $value) {

                // add items to array
                if ($key >= $offset) {$items[$counter++] = $value;}

            }

            // change the size of array to match filtered results
            $items->setSize($counter);

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
        return new self(function ($items) use ($callback):void {

            // change the size of array to be same as current one
            $items->setSize($this->items->getSize());

            // iterate over current items
            foreach ($this->items as $key => $value) {

                if (empty($new_items) && !$callback($key, $value)) {

                    continue;

                }

                // add items to array
                $new_items[] = $value;

            }

            $counter = 0;
            foreach ($new_items ?? [] as $value) {

                // add items to array
                $items[$counter++] = $value;

            }

            // change the size of array to match filtered results
            $items->setSize($counter);

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
        return new self(function ($items) use ($callback):void {

            // change the size of array to be same as current one
            $items->setSize($this->items->getSize());

            // iterate over current items
            foreach ($this->items as $key => $value) {

                if (empty($new_items) && $callback($key, $value)) {

                    continue;

                }

                // add items to array
                $new_items[] = $value;

            }

            $counter = 0;
            foreach ($new_items ?? [] as $value) {

                // add items to array
                $items[$counter++] = $value;

            }

            // change the size of array to match filtered results
            $items->setSize($counter);

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
        return new self(function ($items) use ($limit):void {

            // change the size of array to be same as current one
            $items->setSize($this->items->getSize());

            // iterate over current items
            $counter = 0;
            foreach ($this->items as $key => $value) {

                // add items to array
                if ($key < $limit) {$items[$counter++] = $value;}

            }

            // change the size of array to match filtered results
            $items->setSize($counter);

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
        return new self(function ($items) use ($callback):void {

            // change the size of array to be same as current one
            $items->setSize($this->items->getSize());

            // iterate over current items
            foreach ($this->items as $key => $value) {

                if ($callback($key, $value)) {

                    break;

                }

                // add items to array
                $new_items[] = $value;

            }

            $counter = 0;
            foreach ($new_items ?? [] as $value) {

                // add items to array
                $items[$counter++] = $value;

            }

            // change the size of array to match filtered results
            $items->setSize($counter);

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
        return new self(function ($items) use ($callback):void {

            // change the size of array to be same as current one
            $items->setSize($this->items->getSize());

            // iterate over current items
            foreach ($this->items as $key => $value) {

                if (!$callback($key, $value)) {

                    break;

                }

                // add items to array
                $new_items[] = $value;

            }

            $counter = 0;
            foreach ($new_items ?? [] as $value) {

                // add items to array
                $items[$counter++] = $value;

            }

            // change the size of array to match filtered results
            $items->setSize($counter);

        });

    }

    /**
     * @inheritDoc
     */
    public function when (bool $condition, Closure $condition_meet, ?Closure $condition_not_meet = null):self {

        $condition
            ? $condition_meet($this)
            : (is_null($condition_not_meet)
                ?: $condition_not_meet($this));

        return $this;

    }

    /**
     * @inheritDoc
     */
    public function unless (bool $condition, Closure $condition_meet, ?Closure $condition_not_meet = null):self {

        !$condition
            ? $condition_meet($this)
            : (is_null($condition_not_meet)
                ?: $condition_not_meet($this));

        return $this;

    }

    /**
     * ### Gets the size of the array
     * @since 0.2.0.pre-alpha.M2
     *
     * @return int The size of the array.
     */
    public function getSize ():int {

        // get the size of index array
        return $this->items->getSize();

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
     * {@inheritDoc}
     *
     * @throws Error If $offset is not int.
     */
    public function offsetExists (mixed $offset):bool {

        return is_int($offset) ? isset($this->items[$offset]) : throw new Error('Key needs to be int.');

    }

    /**
     * {@inheritDoc}
     *
     * @throws Error If key is not int.
     * @throws Error If key does not exist in Collection.
     */
    public function offsetGet (mixed $offset):mixed {

        is_int($offset) ?: throw new Error('Key needs to be int.');

        try {

            return $this->items->offsetGet($offset);

        } catch (Throwable) {

            throw new Error(sprintf('Key %s does not exist in Collection.', $offset));

        }

    }

    /**
     * {@inheritDoc}
     *
     * @throws Error If key is not int.
     * @throws Error If key does not exist in Collection.
     */
    public function offsetSet (mixed $offset, mixed $value):void {

        is_int($offset) ?: throw new Error('Key needs to be int.');

        try {

            $this->items->offsetSet($offset, $value);

        } catch (Throwable) {

            throw new Error(sprintf('Key %s does not exist in Collection.', $offset));

        }

    }

    /**
     * {@inheritDoc}
     *
     * @throws Error If $offset is not int.
     */
    public function offsetUnset (mixed $offset):void {

        is_int($offset) ?: throw new Error('Key needs to be int.');

        !$this->offsetExists($offset) ?: $this->items->offsetUnset($offset);

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
     * @return array<int, mixed> Array from collection.
     */
    public function toArray ():array {

        return iterator_to_array($this->items);

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

        return $this->toArray();

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
     * @throws Error If property does not exist.
     *
     * @return SplFixedArray<mixed> Current array.
     */
    public function &__get (string $name):SplFixedArray {

        // check if property name is "items"
        if ($name !== 'items') {

            throw new Error(sprintf('Property %s does not exist.', $name));

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
     * @throws Error If property does not exist.
     *
     * @return void
     */
    public function __set (string $name, SplFixedArray $value):void {

        // check if property name is "items", if is - set to value
        $name === 'items' ? $this->$name = $value : throw new Error(sprintf('Property %s does not exist.', $name));

    }

}