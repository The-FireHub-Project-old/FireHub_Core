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

}