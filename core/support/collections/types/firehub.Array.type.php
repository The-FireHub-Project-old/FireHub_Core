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
use FireHub\Support\Traits\Tappable;
use FireHub\Support\Collections\Enums\SortFlag;
use FireHub\Support\Enums\Order;
use FireHub\Support\Enums\Operators\Comparison;
use Closure, Traversable, Error;

use const COUNT_RECURSIVE;
use const COUNT_NORMAL;
use const SORT_ASC;
use const SORT_DESC;

use function count;
use function array_filter;
use function is_array;
use function sprintf;
use function array_shift;
use function array_unshift;
use function array_pop;
use function array_push;
use function array_count_values;
use function array_column;
use function array_merge_recursive;
use function array_merge;
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
use function array_rand;
use function array_reverse;
use function array_keys;
use function shuffle;
use function array_slice;
use function array_splice;
use function asort;
use function sort;
use function arsort;
use function rsort;
use function ksort;
use function krsort;
use function uasort;
use function usort;
use function uksort;
use function array_key_exists;
use function array_multisort;
use function array_values;
use function array_intersect;
use function array_intersect_assoc;
use function serialize;
use function in_array;
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

    use Tappable;

    /**
     * @inheritDoc
     */
    public function __construct (private Closure $callable) {}

    /**
     * {@inheritDoc}
     *
     * @return array<int|string, mixed> Items from collection.
     */
    public function all ():array {

        return $this->items;

    }

    /**
     * {@inheritDoc}
     *
     * @param bool $multi_dimensional [optional] <p>
     * Count multidimensional items.
     * </p>
     */
    public function count (bool $multi_dimensional = false):int {

        return count($this->items, $multi_dimensional ? COUNT_RECURSIVE : COUNT_NORMAL);

    }

    /**
     * ### Checks if collection is multidimensional
     *
     * Note that any collection that has at least one item as array
     * will be considered as multidimensional collection.
     * @since 0.2.0.pre-alpha.M2
     *
     * @return bool True if collection is multidimensional, false otherwise
     */
    public function isMultiDimensional ():bool {

        return count(array_filter($this->items, 'is_array')) > 0;

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
     * ### Count values from collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param null|int|string $key [optional] <p>
     * Key to count if counting multidimensional array.
     * </p>
     *
     * @throws Error If you have to provide key when counting multidimensional array.
     *
     * @return self New counted collection.
     */
    public function countValues (null|int|string $key = null):self {

        // return new collection
        return new self(function () use ($key):array {

            if (!$this->isMultiDimensional()) {

                return array_count_values($this->items);

            }

            return $key === null
                ? throw new Error('You have to provide key when counting multidimensional array.')
                : array_count_values(array_column($this->items, $key));

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
     * ### Merge new collection with original one
     *
     * If there are same keys on both collections, keys from original collection
     * will be preferred.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return $this New collection.
     */
    public function union (Closure $callback):self {

        foreach($callback() as $key => $value) {

            $this->isset($key) ?: $this->items[$key] = $value;

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
     * @return $this This collection
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
     * @throws Error If one of the original key is neither string nor integer.
     * @throws Error If current and combined collection need to have the same number of items.
     *
     * @return self New combined collection.
     */
    public function combine (self|array $values):self {

        // return new collection
        return new self(function () use ($values):array {

            foreach ($this->items as $value) {

                $items[] = is_string($value) || is_int($value) ? $value : throw new Error('One of the original key is neither string nor integer');

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
    public function difference (self|array ...$compares):self {

        // return new collection
        return new self(function () use ($compares):array {

            foreach ($compares as $compare) {

                $excludes[] = $compare instanceof self ? $compare->items : $compare;

            }

            return array_diff($this->items, ...$excludes ?? []);

        });

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
     * ### Exchanges all keys with their associated values in collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @throws Error If method flip requires that all values be either int or string.
     *
     * @return self New collection with flipped values.
     */
    public function flip ():self {

        // return new collection
        return new self(function ():array {

            foreach ($this->items as $key => $value) {

                is_int($value) || is_string($value) ?: throw new Error('Method flip requires that all values be either int or string');

                $items[$value] = $key;

            }

            return $items ?? [];

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

            return ($this->differenceKeys(array_flip($keys)))->toArray();

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
        return new self(function () use ($callback):array {

            // iterate over current items
            foreach ($this->items as $key => $value) {

                if (empty($items) && !$callback($key, $value)) {

                    continue;

                }

                // add items to array
                $items[$key] = $value;

            }

            // return new items
            return $items ?? [];

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
        return new self(function () use ($callback):array {

            // iterate over current items
            foreach ($this->items as $key => $value) {

                if (empty($items) && $callback($key, $value)) {

                    continue;

                }

                // add items to array
                $items[$key] = $value;

            }

            // return new items
            return $items ?? [];

        });

    }

    /**
     * ### Sorts collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param \FireHub\Support\Enums\Order $order <p>
     * Order type.
     * </p>
     * @param bool $preserve_keys <p>
     * Whether you want to preserve keys from original collection or not.
     * </p>
     * @param \FireHub\Support\Collections\Enums\SortFlag $flag <p>
     * Sorting flag.
     * </p>
     *
     * @return bool True on success, false otherwise.
     */
    public function sort (Order $order = Order::ASC, bool $preserve_keys = false, SortFlag $flag = SortFlag::SORT_REGULAR):bool {

        return $order === Order::ASC
            ? ($preserve_keys
                ? asort($this->items, $flag->value)
                : sort($this->items, $flag->value))
            : ($preserve_keys
                ? arsort($this->items, $flag->value)
                : rsort($this->items, $flag->value));

    }

    /**
     * ### Sorts collection by key
     * @since 0.2.0.pre-alpha.M2
     *
     * @param \FireHub\Support\Enums\Order $order <p>
     * Order type.
     * </p>
     *
     * @return bool True on success, false otherwise.
     */
    public function sortByKey (Order $order = Order::ASC):bool {

        return $order === Order::ASC ? ksort($this->items) : krsort($this->items);

    }

    /**
     * ### Sorts collection by values using a user-defined comparison function
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument is considered to be respectively less than,
     * equal to, or greater than the second.
     * </p>
     * @param bool $preserve_keys <p>
     * Whether you want to preserve keys from original collection or not.
     * </p>
     *
     * @return bool True on success, false otherwise.
     */
    public function sortBy (Closure $callback, bool $preserve_keys = false):bool {

        return $preserve_keys ? uasort($this->items, $callback) : usort($this->items, $callback);

    }

    /**
     * ### Sorts collection by key using a user-defined comparison function
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * The callback comparison function. Function cmp_function should accept two parameters which will be filled by pairs of array keys.
     * The comparison function must return an integer less than, equal to, or greater than zero if the first argument is considered to be respectively less than,
     * equal to, or greater than the second.
     * </p>
     *
     * @return bool True on success, false otherwise.
     */
    public function sortKeyBy (Closure $callback):bool {

        return uksort($this->items, $callback);

    }

    /**
     * ### Sorts collection by multiple fields
     * @since 0.2.0.pre-alpha.M2
     *
     * @param array<int, array<int, string|\FireHub\Support\Enums\Order>> $fields <p>
     * List of fields to sort by.
     * </p>
     *
     * @throws Error If each field has to have both field name and sort value.
     * @throws Error If first key of each field is not integer nor string.
     * @throws Error If sorting by many your collection is not 2-dimensional array.
     * @throws Error If key does not exist.
     * @throws Error If key is missing somewhere.
     *
     * @return bool True on success, false otherwise.
     */
    public function sortByMany (array $fields):bool {

        foreach ($fields as $field) {

            // check if both field name and sort value are present
            isset($field[0]) && isset($field[1]) ?: throw new Error('Each field has to have both field name and sort value.');

            $column = $field[0];
            $order = $field[1];

            // first key of each field must be string
            is_string($column) ?: throw new Error('First key of each field must be integer or string.');

            // when sorting by many your collection must be 2-dimensional array
            is_array($this->items[0]) ?: throw new Error('When sorting by many your collection must be 2-dimensional array.');

            // check if array key exists in the first array
            array_key_exists($column, $this->items[0]) ?: throw new Error(sprintf('Key %s does not exist.', $column));

            // field 1 will be converter to PHP order constants
            // it will default to SORT_ASC is FireHub\Support\Enums\Order is not the type
            $order = $order === Order::DESC ? SORT_DESC : SORT_ASC;

            count(array_keys($this->items)) === count(array_column($this->items, $column)) ?: throw new Error(sprintf('Key %s is missing somewhere.', $column));

            // first array is array of value from selected column
            $multi_sort[] = [...array_column($this->items, $column)];

            // second array is sort order
            $multi_sort[] = $order;

        }

        // attach items at the end of multi-sort
        $multi_sort[] = &$this->items;

        /**
         * In this case we are using spread operator, and PHPStan thinks it is first parameter and complains that int might be used as first parameter.
         * @phpstan-ignore-next-line
         */
        return array_multisort(...$multi_sort);

    }

    /**
     * ### Return new collection with specified number of items
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int $limit <p>
     * If length is given and is positive, then the sequence will have that many elements in it.
     * If length is given and is negative then the sequence will stop that many elements from the end of the collection.
     * If it is omitted, then the sequence will have everything from offset up until the end of the collection.
     * </p>
     *
     * @return self New collection.
     */
    public function take (int $limit):self {

        return $this->slice(0, $limit);

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
        return new self(function () use ($callback):array {

            // iterate over current items
            foreach ($this->items as $key => $value) {

                if ($callback($key, $value)) {

                    break;

                }

                // add items to array
                $items[$key] = $value;

            }

            // return new items
            return $items ?? [];

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
        return new self(function () use ($callback):array {

            // iterate over current items
            foreach ($this->items as $key => $value) {

                if (!$callback($key, $value)) {

                    break;

                }

                // add items to array
                $items[$key] = $value;

            }

            // return new items
            return $items ?? [];

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
     * ### Retrieve only values from collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return self New collection with values only.
     */
    public function values ():self {

        // return new collection
        return new self(function ():array {

            return array_values($this->items);

        });

    }

    /**
     * ### Filters 2-dimensional collection by key and value
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int|string $key <p>
     * Key to filter upon.
     * </p>
     * @param \FireHub\Support\Enums\Operators\Comparison $operator <p>
     * Comparison operator.
     * </p>
     * @param mixed $value <p>
     * Value to search for.
     * </p>
     *
     * @return self New filtered collection.
     */
    public function where (int|string $key, Comparison $operator, mixed $value):self {

        return $this->filter(function ($c_key, $c_value) use ($key, $operator, $value):bool|int {

            return $operator->compare($c_value[$key], $value);

        });

    }

    /**
     * ### Filters 2-dimensional collection by key and value to reject
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int|string $key <p>
     * Key to filter upon.
     * </p>
     * @param \FireHub\Support\Enums\Operators\Comparison $operator <p>
     * Comparison operator.
     * </p>
     * @param mixed $value <p>
     * Value to search for.
     * </p>
     *
     * @return self New filtered collection.
     */
    public function whereNot (int|string $key, Comparison $operator, mixed $value):self {

        return $this->reject(function ($c_key, $c_value) use ($key, $operator, $value):bool|int {

            return $operator->compare($c_value[$key], $value);

        });

    }

    /**
     * ### Filters 2-dimensional collection by key and value between two values
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int|string $key <p>
     * Key to filter upon.
     * </p>
     * @param int $greater_or_equal <p>
     * Search values greater or equal to.
     * </p>
     * @param int $less_or_equal <p>
     * Search values less or equal to.
     * </p>
     *
     * @return self New filtered collection.
     */
    public function whereBetween (int|string $key, int $greater_or_equal, int $less_or_equal):self {

        return $this->where($key, Comparison::GREATER_OR_EQUAL, $greater_or_equal)->where($key, Comparison::LESS_OR_EQUAL, $less_or_equal);

    }

    /**
     * ### Filters 2-dimensional collection by key and reject value between two values
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int|string $key <p>
     * Key to filter upon.
     * </p>
     * @param int $greater_or_equal <p>
     * Search values greater or equal to.
     * </p>
     * @param int $less_or_equal <p>
     * Search values less or equal to.
     * </p>
     *
     * @return self New filtered collection.
     */
    public function whereNotBetween (int|string $key, int $greater_or_equal, int $less_or_equal):self {

        return $this->filter(function ($c_key, $c_value) use ($key, $greater_or_equal, $less_or_equal):bool {

            return Comparison::LESS->compare($c_value[$key], $greater_or_equal) || Comparison::GREATER->compare($c_value[$key], $less_or_equal);

        });

    }

    /**
     * ### Filters 2-dimensional collection by key and value that contains provider list of values
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int|string $key <p>
     * Key to filter upon.
     * </p>
     * @param array<int, mixed> $values <p>
     * List of values to search for.
     * </p>
     *
     * @return self New filtered collection.
     */
    public function whereContains (int|string $key, array $values):self {

        $items = [];
        foreach ($values as $value) {

            $items[] = $this->where($key, Comparison::EQUAL, $value)->toArray();

        }

        // return new collection
        return new self(function () use ($items):array {

            // return new items
            return $items;

        });

    }

    /**
     * ### Filters 2-dimensional collection by key and values that doesn't contain in the list of values
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int|string $key <p>
     * Key to filter upon.
     * </p>
     * @param array<int, mixed> $values <p>
     * List of values to search for.
     * </p>
     *
     * @throws Error If method whereDoesntContain() is not array of arrays, but value on key must be of type array.
     *
     * @return self New filtered collection.
     */
    public function whereDoesntContain (int|string $key, array $values):self {

        // return new collection
        return new self(function () use ($key, $values):array {

            // iterate over current items
            foreach ($this->items as $c_key => $c_value) {

                is_array($c_value) ?: throw new Error(sprintf('Method whereDoesntContain() must be array of arrays, but value on key %s must be of type array', $c_key));

                in_array($c_value[$key], $values) ?: $items[$c_key] = $c_value;

            }

            // return new items
            return $items ?? [];

        });

    }

    /**
     * ### Computes the intersection of collections with values
     * @since 0.2.0.pre-alpha.M2
     *
     * @param \FireHub\Support\Collections\Types\Array_Type|array<mixed, mixed> ...$collections <p>
     * Collections or arrays to compare values against.
     * </p>
     *
     * @return self New intersected collection that contains the values in original collection whose values exist in all collections from parameter.
     */
    public function intersect (self|array ...$collections):self {

        // return new collection
        return new self(function () use ($collections):array {

            foreach ($collections as $collection) {

                $arrays[] = $collection instanceof self ? $collection->items : $collection;

            }

            return array_intersect($this->items, ...$arrays ?? []);

        });

    }

    /**
     * ### Computes the intersection of collections with keys
     * @since 0.2.0.pre-alpha.M2
     *
     * @param \FireHub\Support\Collections\Types\Array_Type|array<mixed, mixed> ...$collections <p>
     * Collections or arrays to compare values against.
     * </p>
     *
     * @return self New intersected collection that contains all the values in original collection whose keys exist in all collections from parameter.
     */
    public function intersectKey (self|array ...$collections):self {

        // return new collection
        return new self(function () use ($collections):array {

            foreach ($collections as $collection) {

                $arrays[] = $collection instanceof self ? $collection->items : $collection;

            }

            return array_intersect_key($this->items, ...$arrays ?? []);

        });

    }

    /**
     * ### Computes the intersection of collections with additional index check
     * @since 0.2.0.pre-alpha.M2
     *
     * @param \FireHub\Support\Collections\Types\Array_Type|array<mixed, mixed> ...$collections <p>
     * Collections or arrays to compare values against.
     * </p>
     *
     * @return self New intersected collection that contains the values in original collection whose keys and values exist in all collections from parameter.
     */
    public function intersectAssoc (self|array ...$collections):self {

        // return new collection
        return new self(function () use ($collections):array {

            foreach ($collections as $collection) {

                $arrays[] = $collection instanceof self ? $collection->items : $collection;

            }

            return array_intersect_assoc($this->items, ...$arrays ?? []);

        });

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
     * {@inheritDoc}
     *
     * @return array<int|string, mixed> Array from collection.
     */
    public function toArray ():array {

        return $this->items;

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