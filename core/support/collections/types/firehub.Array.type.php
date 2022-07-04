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
use function sprintf;
use function array_shift;
use function array_unshift;
use function array_pop;
use function array_push;
use function array_merge_recursive;
use function array_merge;
use function array_filter;
use function is_array;
use function is_string;
use function is_int;
use function array_combine;
use function is_callable;
use function array_search;
use function array_diff_key;
use function array_diff;
use function array_diff_assoc;
use function array_unique;
use function array_intersect_key;
use function array_flip;
use function array_column;
use function array_rand;
use function array_reverse;
use function array_keys;
use function shuffle;
use function array_slice;
use function array_splice;
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
     * @param int|string $key <p>
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
    public function add (int|string $key, mixed $value):void {

        !$this->offsetExists($key) ? $this->offsetSet($key, $value) : throw new Error(sprintf('Key %s already exist.', $key));

    }

    /**
     * ### Replaces an item at the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int|string $key <p>
     * Collection item key.
     * </p>
     * @param mixed $value <p>
     * Collection item value.
     * </p>
     *
     * @throws Error If $offset is not int or string or does not exist.
     */
    public function replace (mixed $key, mixed $value):void {

        $this->offsetExists($key) ? $this->offsetSet($key, $value) : throw new Error(sprintf('Key %s does not exist.', $key));

    }

    /**
     * {@inheritDoc}
     *
     * @param int|string $key <p>
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
     * @param int|string $key <p>
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
     * @param int|string $key <p>
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
     * @param int|string $key <p>
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

        foreach ($this->items as $key => &$value) {

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
        return new self(function () use ($callback):array {

            // iterate over current items
            foreach ($this->items as $key => $value) {

                // add current callback value to same key
                $items[] = $callback($key, $value);

            }

            // return new items
            return $items ?? [];

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
            $collection->items[$key] = $value;

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

        foreach($callback() as $key => $value) {

            $this->items[$key] = $value;

        }

        return $this;

    }

    /**
     * ### Merge collection recursively
     * @since 0.2.0.pre-alpha.M2
     *
     * Merges the elements of one or more arrays together so that the values of one are appended
     * to the end of the previous one.
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return self New collection
     */
    public function mergeRecursive (Closure $callback):self {

        $this->items = array_merge_recursive($this->items, $callback());

        return $this;

    }

    /**
     * @inheritDoc
     */
    public function filter (Closure $callback):self {

        // return new collection
        return new self(function () use ($callback):array {

            // iterate over current items
            foreach ($this->items as $key => $value) {

                // add items to array if callback is true
                !$callback($key, $value) ?: $items[$key] = $value;

            }

            // return new items
            return $items ?? [];

        });

    }

    /**
     * @inheritDoc
     */
    public function reject (Closure $callback):self {

        // return new collection
        return new self(function () use ($callback):array {

            // iterate over current items
            foreach ($this->items as $key => $value) {

                // add items to array if callback is false
                $callback($key, $value) ?: $items[$key] = $value;

            }

            // return new items
            return $items ?? [];

        });

    }

    /**
     * ### Collapses a collection of arrays into a single, flat collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return self New filtered collection.
     */
    public function collapse ():self {

        // return new collection
        return new self(function ():array {

            // collapse with all items that are array themselves
            return array_merge(...array_filter($this->items, 'is_array'));

        });

    }

    /**
     * ### Creates a collection by using one collection or array for keys and another for its values
     *
     * Note that illegal values for keys from existing collection will be converted to string.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param \FireHub\Support\Collections\Types\Array_Type|array<mixed, mixed> $values <p>
     * Collection or array of values to be used for combining.
     * </p>
     *
     * @throws Error If current and combined collection don't have the same number of items.
     *
     * @return self New combined collection.
     */
    public function combine (self|array $values):self {

        // return new collection
        return new self(function () use ($values):array {

            foreach ($this->items as $value) {

                $items[] = is_string($value) || is_int($value) ? $value : throw new Error('One of the original key is neither string or integer');

            }

            return $this->count() !== count($values) // check if array size is the same on both collections
                ? throw new Error('Current and combined collection need to have the same number of items.')
                : array_combine($items ?? [], is_array($values) ? $values : $values->items);

        });

    }

    /**
     * @inheritDoc
     */
    public function contains (mixed $search):bool {

        if (is_callable($search)) { // $search is callable

            // iterate over current items
            foreach ($this->items as $key => $value) {

                // if callback is true return early true
                if ($search($key, $value)) {

                    return true;

                }

            }

        } else { // $search is not callable

            return (bool)array_search($search, $this->items, true);

        }

        // if no condition was meet, return false
        return false;

    }

    /**
     * ### Computes the difference of collections or arrays using keys for comparison
     *
     * Compares the keys from array against the keys from collection or array and returns the difference.
     * This method is like differenceValues() except the comparison is done on the keys instead of the values.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param \FireHub\Support\Collections\Types\Array_Type|array<mixed, mixed> ...$compares <p>
     * Collection or array to compare against.
     * </p>
     *
     * @return self New collection.
     */
    public function differenceKeys (self|array ...$compares):self {

        // return new collection
        return new self(function () use ($compares):array {

            foreach ($compares as $compare) {

                $excludes[] = $compare instanceof self ? $compare->items : $compare;

            }

            return array_diff_key($this->items, ...$excludes ?? []);

        });

    }

    /**
     * ### Computes the difference of collections or arrays
     *
     * Compares existing collection against one or more other collection or array
     * and returns the values in the new collection that are not present in any of the other collections.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param \FireHub\Support\Collections\Types\Array_Type|array<mixed, mixed> ...$compares <p>
     * Collection or array to compare against.
     * </p>
     *
     * @return self New collection.
     */
    public function differenceValues (self|array ...$compares):self {

        // return new collection
        return new self(function () use ($compares):array {

            foreach ($compares as $compare) {

                $excludes[] = $compare instanceof self ? $compare->items : $compare;

            }

            return array_diff($this->items, ...$excludes ?? []);

        });

    }

    /**
     * ### Computes the difference of collections or arrays with additional index check
     *
     * Compare collections or arrays against collection or array and returns the difference.
     * Unlike differenceValues(), the keys are also used in the comparison.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param \FireHub\Support\Collections\Types\Array_Type|array<mixed, mixed> ...$compares <p>
     * Collection or array to compare against.
     * </p>
     *
     * @return self New collection.
     */
    public function differenceAssoc (self|array ...$compares):self {

        // return new collection
        return new self(function () use ($compares):array {

            foreach ($compares as $compare) {

                $excludes[] = $compare instanceof self ? $compare->items : $compare;

            }

            return array_diff_assoc($this->items, ...$excludes ?? []);

        });

    }

    /**
     * ### Removes unique values from an array
     *
     * Method validates only values, and ignores keys.
     * @since 0.2.0.pre-alpha.M2
     *
     * @return self New collection with duplicated values.
     */
    public function duplicates ():self {

        // return new collection
        return new self(function ():array {

            return array_diff_assoc($this->items, array_unique($this->items));

        });

    }

    /**
     * ### Removes duplicate values from an array
     *
     * Method validates only values, and ignores keys.
     * @since 0.2.0.pre-alpha.M2
     *
     * @return self New collection with unique values.
     */
    public function unique ():self {

        // return new collection
        return new self(function ():array {

            return array_unique($this->items);

        });

    }

    /**
     * @inheritDoc
     */
    public function every (Closure $callback):bool {

        // iterate over current items
        foreach ($this->items as $key => $value) {

            // if callback is true return early true
            if (!$callback($key, $value)) {

                return false;

            }

        }

        return true;

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
        return new self(function () use ($keys):array {

            return array_intersect_key($this->items, array_flip($keys));

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
        return new self(function () use ($keys):array {

            //return array_diff_key($this->items, array_flip($keys));
            return ($this->differenceKeys(array_flip($keys)))->all();

        });

    }

    /**
     * ### Pad array to the specified length with a value
     *
     * You will get a copy of the input padded to size specified by pad_size with value pad_value.
     * If pad_size is positive then the array is padded on the right, if it's negative then on the left.
     * If the absolute value of pad_size is less than or equal to the length of the input then no padding takes place.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int $size <p>
     * New size of the array.
     * </p>
     *
     * @param mixed $value <p>
     * Value to pad if input is less than pad_size.
     * </p>
     *
     * @return self New collection.
     */
    public function pad (int $size, mixed $value):self {

        // return new collection
        return new self(function () use ($size, $value):array {

            return array_pad($this->items, $size, $value);

        });

    }

    /**
     * ### Separate elements that pass a given truth test from those that do not
     *
     * New partitioned collection will contain two child collections inside.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return self New collection.
     */
    public function partition (Closure $callback):self {

        // return new collection
        return new self(function () use ($callback):array {

            $passed = new self(function () {return [];});
            $failed = new self(function () {return [];});

            // iterate over current items
            foreach ($this->items as $key => $value) {

                $callback($key, $value) ? $passed->items[$key] = $value : $failed->items[$key] = $value;

            }

            return [$passed, $failed];

        });

    }

    /**
     * ### Get the values from given key
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int|string $column <p>
     * The column of values to return. This value may be the integer key of the column you wish to retrieve,
     * or it may be the string key name for an associative array.
     * It may also be NULL to return complete arrays (useful together with index_key to reindex the array).
     * </p>
     * @param int|string|null $key [optional] <p>
     * The column to use as the index/keys for the returned array.
     * This value may be the integer key of the column, or it may be the string key name.
     * </p>
     *
     * @return self New plucked collection.
     */
    public function pluck (int|string $column, int|string|null $key = null):self {

        // return new collection
        return new self(function () use ($column, $key):array {

            return array_column($this->items, $column, $key);

        });

    }

    /**
     * ### Pick one or more random values out of the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int $number [optional] <p>
     * Specifies how many entries you want to pick.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * Whether you want to preserve keys from original collection or not.
     * </p>
     *
     * @throws Error If asked number of items is greater than total number of items in collection.
     *
     * @return mixed If you are picking only one entry, returns the key for a random entry. Otherwise, it returns an array of keys for the random entries.
     */
    public function random (int $number = 1, bool $preserve_keys = false):mixed {

        // check if asked number of items is greater than total number of items in collection
        !($number > $this->count()) ?: throw new Error(sprintf('Asked random values are %d, and are greater then total number of items in collection %d.', $number, $this->count()));

        // get the random keys from collection items
        $keys = array_rand($this->items, $number);

        // if keys are not array
        if (!is_array($keys)) {

            return $this->items[$keys];

        }

        if ($preserve_keys) { // if we turn on preserved key

            foreach ($keys as $key) {

                $items[$key] = $this->items[$key];

            }

        } else { // if we turn off preserved key

            foreach ($keys as $key) {

                $items[] = $this->items[$key];

            }

        }

        return $items ?? [];

    }

    /**
     * ### Reverse the order of collection items
     * @since 0.2.0.pre-alpha.M2
     *
     * @param bool $preserve_keys [optional] <p>
     * Whether you want to preserve keys from original collection or not.
     * </p>
     *
     * @return self New reversed collection.
     */
    public function reverse (bool $preserve_keys = false):self {

        // return new collection
        return new self(function () use ($preserve_keys):array {

            return array_reverse($this->items, $preserve_keys);

        });

    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $value <p>
     * The searched value.
     * If needle is a string, the comparison is done in a case-sensitive manner.
     * </p>
     * @param int|string|false $second_dimension_column [optional] <p>
     * Allows you to search second dimension on multidimensional array.
     * </p>
     *
     * @return int|string|false The key for needle if it is found in the collection, false otherwise. If needle is found in haystack more than once, the first matching key is returned.
     */
    public function search (mixed $value, int|string|false $second_dimension_column = false):int|string|false {

        return $second_dimension_column
            ? array_search($value, array_combine(array_keys($this->items), array_column($this->items, $second_dimension_column)), true)
            : array_search($value, $this->items, true);

    }

    /**
     * ### Reverse the order of collection items
     * @since 0.2.0.pre-alpha.M2
     *
     * @param bool $preserve_keys [optional] <p>
     * Whether you want to preserve keys from original collection or not.
     * </p>
     *
     * @return bool True if shuffled was successful, false otherwise.
     */
    public function shuffle (bool $preserve_keys = false):bool {

        // if we want to preserve keys
        if ($preserve_keys) {

            // get of keys from collection
            $keys = array_keys($this->items);

            // shuffle out keys
            shuffle($keys);

            // add values from original items to shuffled one
            foreach($keys as $key) {

                $items[$key] = $this->items[$key];

            }

            // attach shuffled items back to collection
            $this->items = $items ?? [];

            return true;

        }

        // shuffle items without preserving keys
        return shuffle($this->items);

    }

    /**
     * ### Extract a slice of the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int $offset <p>
     * If offset is non-negative, the sequence will start at that offset in the collection.
     * If offset is negative, the sequence will start that far from the end of the collection.
     * </p>
     * @param null|int $length [optional] <p>
     * If length is given and is positive, then the sequence will have that many elements in it.
     * If length is given and is negative then the sequence will stop that many elements from the end of the collection.
     * If it is omitted, then the sequence will have everything from offset up until the end of the collection.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * Note that array_slice will reorder and reset the collection indices by default.
     * You can change this behaviour by setting preserve_keys to true
     * </p>
     *
     * @return self New sliced collection.
     */
    public function slice (int $offset, ?int $length = null, bool $preserve_keys = false):self {

        // return new collection
        return new self(function () use ($offset, $length, $preserve_keys):array {

            return array_slice($this->items, $offset, $length, $preserve_keys);

        });

    }

    /**
     * ### Remove a portion of the array and replace it with something else
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int $offset <p>
     * If offset is positive then the start of removed portion is at that offset from the beginning of the input collection.
     * If offset is negative then it starts that far from the end of the input collection.
     * </p>
     * @param null|int $length [optional] <p>
     * If length is omitted, removes everything from offset to the end of the collection.
     * If length is specified and is positive, then that many elements will be removed.
     * If length is specified and is negative then the end of the removed portion will be that many elements from the end of the collection.
     * </p>
     * @param \FireHub\Support\Collections\Types\Array_Type|array<mixed, mixed> $replacement [optional] <p>
     * If replacement array is specified, then the removed elements are replaced with elements from this collection.
     * If offset and length are such that nothing is removed, then the elements from the replacement array or collection are inserted in the place specified by the offset.
     * Keys in replacement array are not preserved.
     * </p>
     *
     * @return self New spliced collection.
     */
    public function splice (int $offset, ?int $length = null, self|array $replacement = []):self {

        // return new collection
        return new self(function () use ($offset, $length, $replacement):array {

            array_splice($this->items, $offset, $length, $replacement instanceof self ? $replacement->items : $replacement);

            return $this->items;

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

        return $this->slice($offset);

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