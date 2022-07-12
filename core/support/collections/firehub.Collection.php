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

use FireHub\Support\Collections\Types\ {
    Array_Type, Index_Type, Lazy_Type, Object_Type
};
use FireHub\Support\Collections\Predefined\Fill;
use Closure;

/**
 * ### Data collection
 *
 * This class is a wrapper for working with lists of data.
 * @since 0.2.0.pre-alpha.M2
 *
 * @package FireHub\Support\Collections
 */
final class Collection {

    /**
     * ### Constructor
     *
     * Prevents instantiation of main collection class
     * @since 0.2.0.pre-alpha.M2
     */
    private function __construct () {}

    /**
     * ### Basic collection type
     *
     * Basic collection type is collection that has main focus of performance
     * and doesn't concern itself about memory consumption.
     * This collection can hold any type of data.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $source <p>
     * Items list to create collection.
     * </p>
     *
     * @return \FireHub\Support\Collections\Types\Array_Type
     */
    public static function create (Closure $source):Collectable {

        return new Array_Type($source);

    }

    /**
     * ### Index collection type
     *
     * Index collection allows only integers as keys, but it is faster
     * and uses less memory than basic collection.
     * This collection type must be resized manually and allows only
     * integers within the range as indexes.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $source <p>
     * Items list to create collection.
     * </p>
     * @param int $size <p>
     * Size argument lets you change the size of an array to the new size. If size is less than the current array size,
     * any values after the new size will be discarded. If size is greater than the current array size,
     * the array will be padded with null values.
     * </p>
     *
     * @return \FireHub\Support\Collections\Types\Index_Type
     */
    public static function index (Closure $source, int $size):Collectable {

        return new Index_Type($source, $size);

    }

    /**
     * ### Lazy collection type
     *
     * Index collection type must be resized manually
     * and allows only integers within the range as indexes.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $source <p>
     * Items list to create collection.
     * </p>
     *
     * @return \FireHub\Support\Collections\Types\Lazy_Type
     */
    public static function lazy (Closure $source):Collectable {

        return new Lazy_Type($source);

    }

    /**
     * ### Object collection type
     *
     * Index collection type must be resized manually
     * and allows only integers within the range as indexes.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $source <p>
     * Items list to create collection.
     * </p>
     *
     * @return \FireHub\Support\Collections\Types\Object_Type
     */
    public static function object (Closure $source):Collectable {

        return new Object_Type($source);

    }

    /**
     * ### Fill the collection with values
     * @since 0.2.0.pre-alpha.M2
     *
     * @param int $start_index <p>
     * The first index of the returned collection.
     * Supports non-negative indexes only.
     * <p>
     * @param int $length <p>
     * Number of elements to insert.
     * </p>
     * @param mixed $value <p>
     * Value to use for filling.
     * </p>
     *
     * @return \FireHub\Support\Collections\Predefined\Fill
     */
    public static function fill (int $start_index, int $length, mixed $value):Fill {

        return new Fill($start_index, $length, $value);

    }

}