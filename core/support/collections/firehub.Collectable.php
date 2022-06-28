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

}