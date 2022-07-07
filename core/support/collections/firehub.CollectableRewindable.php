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

namespace FireHub\Support\Collections;

use FireHub\Support\Contracts\Iterator\Rewindable;
use Closure;

/**
 * ### Collection rewindable contract
 *
 * Contract contains all methods that every rewindable collection type must have.
 * @since 0.2.0.pre-alpha.M2
 *
 * @package FireHub\Support\Collections
 */
interface CollectableRewindable extends Collectable, Rewindable {

    /**
     * ### Gets item from collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param mixed $key <p>
     * Collection item key.
     * </p>
     *
     * @return mixed Item value.
     */
    public function get (mixed $key):mixed;

    /**
     * ### Sets an item at the collection
     *
     * If key already exists, it will replace the original value.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param mixed $key <p>
     * Collection item key.
     * </p>
     * @param mixed $value <p>
     * Collection item value.
     * </p>
     *
     * @return void
     */
    public function set (mixed $key, mixed $value):void;

    /**
     * ### Checks if item exist in the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param mixed $key <p>
     * Collection item key.
     * </p>
     *
     * @return bool True if key exist, false otherwise.
     */
    public function isset (mixed $key):bool;

    /**
     * ### Removes an item at the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param mixed $key <p>
     * Collection item key.
     * </p>
     *
     * @return void
     */
    public function unset (mixed $key):void;

    /**
     * ### Perform function on each item from collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return $this This collection.
     */
    public function each (Closure $callback):self;

    /**
     * ### Apply a user supplied function to every collection item
     *
     * This method will transform existing collection.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return $this This collection.
     */
    public function walk (Closure $callback):self;

    /**
     * ### Applies the callback to the collection items
     *
     * This method will create new collection.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return self New collection.
     */
    public function map (Closure $callback):self;

    /**
     * ### Breaks this collection into smaller collections and applies user function on each collection items
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int $size <p>
     * Size of each collection.
     * </p>
     * @param Closure $callback <p>
     * Callback of each collection.
     * </p>
     *
     * @return void
     */
    public function chunk (int $size, Closure $callback):void;

    /**
     * ### Merge new collection with original one
     *
     * If there are same keys on both collections, keys from new collection
     * will replace keys from original collection.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return $this This collection.
     */
    public function merge (Closure $callback):self;

    /**
     * ### Determines whether the collection contains a given item
     * @since 0.2.0.pre-alpha.M2
     *
     * @param mixed $search <p>
     * Item to search.
     * </p>
     *
     * @return bool True if item exists, false otherwise.
     */
    public function contains (mixed $search):bool;

    /**
     * ### Verify that all elements of a collection pass a given truth test
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return bool True all elements of a collection passed the test, false otherwise.
     */
    public function every (Closure $callback):bool;

    /**
     * ### Searches the collection for a given value and returns the first corresponding key if successful
     * @since 0.2.0.pre-alpha.M2
     *
     * @param mixed $value <p>
     * The searched value.
     * If needle is a string, the comparison is done in a case-sensitive manner.
     * </p>
     *
     * @return mixed The key for needle if it is found in the collection, false otherwise. If needle is found in haystack more than once, the first matching key is returned.
     */
    public function search (mixed $value):mixed;

    /**
     * ### Execute the given callback when the first argument given to the method evaluates to true
     * @since 0.2.0.pre-alpha.M2
     *
     * @param bool $condition <p>
     * Condition to meet.
     * </p>
     * @param Closure $condition_meet <p>
     * Callback if condition is meet.
     * </p>
     * @param ?Closure $condition_not_meet [optional] <p>
     * Callback if condition is not meet.
     * </p>
     *
     * @return $this This collection.
     */
    public function when (bool $condition, Closure $condition_meet, ?Closure $condition_not_meet = null):self;

    /**
     * ### Execute the given callback unless the first argument given to the method evaluates to true
     * @since 0.2.0.pre-alpha.M2
     *
     * @param bool $condition <p>
     * Condition to meet.
     * </p>
     * @param Closure $condition_meet <p>
     * Callback if condition is meet.
     * </p>
     * @param ?Closure $condition_not_meet [optional] <p>
     * Callback if condition is not meet.
     * </p>
     *
     * @return $this This collection.
     */
    public function unless (bool $condition, Closure $condition_meet, ?Closure $condition_not_meet = null):self;

}