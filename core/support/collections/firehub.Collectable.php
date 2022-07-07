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

use Closure;

/**
 * ### Collection base contract
 *
 * Contract contains all methods that every collection type must have.
 * @since 0.2.0.pre-alpha.M2
 *
 * @package FireHub\Support\Collections
 */
interface Collectable {

    /**
     * ### Constructor
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callable <p>
     * Data from callable source.
     * </p>
     */
    public function __construct (Closure $callable);

    /**
     * ### Get entire collection as array
     * @since 0.2.0.pre-alpha.M2
     *
     * @return array<int|string, mixed> Array from collection.
     */
    public function all ():array;

    /**
     * ### Filter elements of the Collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return self New filtered collection.
     */
    public function filter (Closure $callback):self;

    /**
     * ### Remove elements of the Collection
     *
     * This method is reverse from filter method.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return self New filtered collection.
     */
    public function reject (Closure $callback):self;

    /**
     * ### Get all items in the collection with the specified keys
     * @since 0.2.0.pre-alpha.M2
     *
     * @param mixed ...$keys <p>
     * List of keys to return.
     * </p>
     *
     * @return self New collection.
     */
    public function only (mixed ...$keys):self;

    /**
     * ### Get all items in the collection except for those with the specified keys
     * @since 0.2.0.pre-alpha.M2
     *
     * @param mixed ...$keys <p>
     * List of keys to remove.
     * </p>
     *
     * @return self New collection.
     */
    public function except (mixed ...$keys):self;

    /**
     * ### Tap the collection
     *
     * Passes the collection to the given callback, allowing you to "tap" into the collection at a specific point
     * and do something with the items while not affecting the collection itself.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return self This collection.
     */
    public function tap (Closure $callback):self;

}