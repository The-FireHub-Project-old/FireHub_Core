<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package.
 * @since 0.2.1.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license OSL Open Source License version 3 - [https://opensource.org/licenses/OSL-3.0](https://opensource.org/licenses/OSL-3.0)
 *
 * @package FireHub\Support\Collections
 * @version 1.0
 */

namespace FireHub\Support\LowLevel;

use Traversable;

use function is_iterable;
use function iterator_count;

/**
 * ### Iterator low level class
 * @since 0.2.1.pre-alpha.M2
 *
 * @package FireHub\Support\Collections
 */
final class Iterator {

    /**
     * ### Checks if value is iterator
     * @since 0.2.1.pre-alpha.M2
     *
     * @param mixed $value <p>
     * Value to check.
     * </p>
     *
     * @return bool True if value is array, false otherwise
     */
    public static function isIterator (mixed $value):bool {

        return is_iterable($value);

    }

    /**
     * ### Checks if iterator is empty
     * @since 0.2.1.pre-alpha.M2
     *
     * @param Traversable<mixed> $iterable <p>
     * Array to check.
     * </p>
     *
     * @return bool True if array is empty, false otherwise
     */
    public static function isEmpty (Traversable $iterable):bool {

        return self::count($iterable) === 0;

    }

    /**
     * ### Count the elements in an iterator
     * @since 0.2.1.pre-alpha.M2
     *
     * @param Traversable<mixed> $iterable <p>
     * The iterator being counted.
     * </p>
     *
     * @return int Number of elements in array.
     */
    public static function count (Traversable $iterable):int {

        return iterator_count($iterable);

    }

}