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

use Throwable, Error;

use const COUNT_RECURSIVE;
use const COUNT_NORMAL;
use const ARRAY_FILTER_USE_BOTH;
use const ARRAY_FILTER_USE_KEY;

use function is_array;
use function count;
use function array_count_values;
use function array_shift;
use function array_unshift;
use function array_pop;
use function array_push;
use function array_column;
use function array_merge;
use function array_merge_recursive;
use function is_string;
use function is_int;
use function array_combine;
use function array_search;
use function array_diff;
use function array_diff_key;
use function array_diff_assoc;
use function array_unique;
use function array_pad;
use function sprintf;
use function array_rand;
use function array_intersect;
use function array_intersect_key;
use function array_intersect_assoc;
use function array_keys;
use function is_null;
use function range;
use function array_filter;

/**
 * ### Array low level class
 * @since 0.2.1.pre-alpha.M2
 *
 * @package FireHub\Support\Collections
 */
final class Arr {

    /**
     * ### Checks if value is array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param mixed $value <p>
     * Value to check.
     * </p>
     *
     * @return bool True if value is array, false otherwise
     */
    public static function isArray (mixed $value):bool {

        return is_array($value);

    }

    /**
     * ### Checks if array is empty
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * Array to check.
     * </p>
     *
     * @return bool True if array is empty, false otherwise
     */
    public static function isEmpty (array $array):bool {

        return self::count($array) === 0;

    }

    /**
     * ### Checks if array is multidimensional
     *
     * Note that any array that has at least one item as array
     * will be considered as multidimensional array.
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * Array to check.
     * </p>
     *
     * @return bool True if array is multidimensional, false otherwise
     */
    public static function isMultiDimensional (array $array):bool {

        return Arr::count(Arr::filter($array, [self::class, 'isArray'])) > 0;

    }

    /**
     * ### Checks if array is associative
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * Array to check.
     * </p>
     *
     * @return bool True if array is associative, false otherwise
     */
    public static function isAssociative (array $array):bool {

        if (self::isEmpty($array)) return false;

        return self::keys($array) !== self::range(0, self::count($array) - 1);

    }

    /**
     * ### Counts all elements in the array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * Array to count.
     * </p>
     * @param bool $multi_dimensional [optional] <p>
     * Count multidimensional items.
     * </p>
     *
     * @return int Number of elements in array.
     */
    public static function count (array $array, bool $multi_dimensional = false):int {

        return count($array, $multi_dimensional ? COUNT_RECURSIVE : COUNT_NORMAL);

    }

    /**
     * ### Counts all the values of an array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * The array of values to count.
     * </p>
     * @param null|int|string $key [optional] <p>
     * Key to count if counting multidimensional array.
     * </p>
     *
     * @throws Error If you have to provide key when counting multidimensional array.
     *
     * @return array<int|string, int> An associative array of values from input as keys and their count as value.
     */
    public static function countValues (array $array, null|int|string $key = null):array {

        if (!self::isMultiDimensional($array)) {

            return array_count_values($array);

        }

        return $key === null
            ? throw new Error('You have to provide key when counting multidimensional array.')
            : array_count_values(self::column($array, $key));

    }

    /**
     * ### Removes an item at the beginning of an array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> &$array <p>
     * Array to shift.
     * </p>
     *
     * @return mixed he shifted value, or null if array is empty or is not an array.
     */
    public static function shift (array &$array):mixed {

        return array_shift($array);

    }

    /**
     * ### Prepend elements to the beginning of an array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> &$array <p>
     * Array to unshift.
     * </p>
     * @param mixed ...$values [optional] <p>
     * The prepended variables.
     * </p>
     *
     * @return int The number of elements in the array.
     */
    public static function unshift (array &$array, mixed ...$values):int {

        return array_unshift($array, $values);

    }

    /**
     * ### Pop the element off the end of array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> &$array <p>
     * Array to pop.
     * </p>
     *
     * @return mixed The last value of array. If array is empty (or is not an array), null will be returned.
     */
    public static function pop (array &$array):mixed {

        return array_pop($array);

    }

    /**
     * ### Push elements onto the end of array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> &$array <p>
     * Array to unshift.
     * </p>
     * @param mixed ...$values [optional] <p>
     * The prepended variables.
     * </p>
     *
     * @return int The number of elements in the array.
     */
    public static function push (array &$array, mixed ...$values):int {

        return array_push($array, ...$values);

    }

    /**
     * ### Return the values from a single column in the input array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * A multi-dimensional array (record set) from which to pull a column of values.
     * </p>
     * @param int|string|null $key <p>
     * The column of values to return.
     * This value may be the integer key of the column you wish to retrieve, or it may be the string key name for an associative array.
     * It may also be NULL to return complete arrays (useful together with index_key to reindex the array).
     * </p>
     * @param int|string|null $index [optional] <p>
     * The column to use as the index/keys for the returned array.
     * This value may be the integer key of the column, or it may be the string key name.
     * The value is cast as usual for array keys.
     * </p>
     *
     * @return array<int|string, mixed> Array of values representing a single column from the input array.
     */
    public static function column (array $array, int|string|null $key, int|string|null $index = null) {

        return array_column($array, $key, $index);

    }

    /**
     * ### Merges the elements of one or more arrays together
     *
     * If the input arrays have the same string keys, then the later value for that key will overwrite the previous one.
     * If the arrays contain numeric keys, the later value will be appended.
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int, mixed> ...$arrays [optional] <p>
     * Variable list of arrays to merge.
     * </p>
     *
     * @return array<int, mixed> The resulting array.
     */
    public static function merge (array ...$arrays):array {

        return array_merge(...$arrays);

    }

    /**
     * ### Merge two or more arrays recursively
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> ...$arrays [optional] <p>
     * Variable list of arrays to recursively merge.
     * </p>
     *
     * @return array<int|string, mixed> The resulting array.
     */
    public static function mergeRecursive (array ...$arrays):array {

        return array_merge_recursive(...$arrays);

    }


    /**
     * ### Collapses array of arrays into a single, flat array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * Multidimensional array to collapse.
     * </p>
     *
     * @throws Error If array is not multi-dimensional.
     *
     * @return array<int|string, mixed> The resulting array.
     */
    public static function collapse (array $array) {

        /**
         * PHPStan stan reports that self::merge expects array<int, mixed>, but that
         * is already evaluated in self::isMultiDimensional method.
         */
        return self::isMultiDimensional($array)
            ? self::merge(...Arr::filter($array, [self::class, 'isArray'])) // @phpstan-ignore-line
            : throw new Error('Array need to be multi-dimensional to be able to collapse.');

    }

    /**
     * ### Creates an array by using one array for keys and another for its values
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $keys <p>
     * Array of keys to be used. Illegal values for key will be converted to string.
     * </p>
     * @param array<int|string, mixed> $values <p>
     * Array of values to be used.
     * </p>
     *
     * @throws Error If one of the original key is neither string nor integer.
     * @throws Error If current and combined arrays need to have the same number of items.
     *
     * @return array<int|string, mixed> The combined array, false if the number of elements for each array isn't equal or if the arrays are empty.
     */
    public static function combine (array $keys, array $values):array {

        try {

            foreach ($keys as $value) {

                $items[] = is_string($value) || is_int($value) ? $value : throw new Error('One of the original key is neither string nor integer');

            }

            return array_combine($items ?? [], $values);

        } catch (Throwable $error) {

            if (self::count($keys) !== self::count($values)) throw new Error('Current and combined array need to have the same number of items');

            throw new Error($error->getMessage());

        }

    }

    /**
     * ### Searches the array for a given value and returns the first corresponding key if successful
     * @since 0.2.1.pre-alpha.M2
     *
     * @param mixed $value <p>
     * The searched value.
     * </p>
     * @param array<int|string, mixed> $array <p>
     * Array to search.
     * </p>
     * @param int|string|false $second_dimension_column [optional] <p>
     * Allows you to search second dimension on multidimensional array.
     * </p>
     *
     * @return false|int|string The key for needle if it is found in the array, false otherwise.
     */
    public static function search (mixed $value, array $array, int|string|false $second_dimension_column = false):false|int|string {

        return $second_dimension_column
            ? array_search($value, array_combine(array_keys($array), array_column($array, $second_dimension_column)), true)
            : array_search($value, $array, true);

    }

    /**
     * ### Searches the array for a given value and returns the list of corresponding keys if successful
     * @since 0.2.1.pre-alpha.M2
     *
     * @param mixed $value <p>
     * The searched value.
     * </p>
     * @param array<int|string, mixed> $array <p>
     * Array to search.
     * </p>
     *
     * @return array<int, int|string> The keys for needle if it is found in the array.
     */
    public static function searchAll (mixed $value, array $array):array {

        return self::keys($array, $value);

    }

    /**
     * ### Computes the difference of arrays
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * The array to compare from.
     * </p>
     * @param array<int|string, mixed> ...$excludes [optional] <p>
     * An array to compare against.
     * </p>
     *
     * @return array<int|string, mixed> An array containing all the entries from array1 that are not present in any of the other arrays.
     */
    public static function difference (array $array, array ...$excludes):array {

        return array_diff($array, ...$excludes);

    }

    /**
     * ### Computes the difference of arrays using keys for comparison
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * The array to compare from.
     * </p>
     * @param array<int|string, mixed> ...$excludes [optional] <p>
     * An array to compare against.
     * </p>
     *
     * @return array<int|string, mixed> An array containing all the entries from array1 that are not present in any of the other arrays.
     */
    public static function differenceKey (array $array, array ...$excludes):array {

        return array_diff_key($array, ...$excludes);

    }

    /**
     * ### Computes the difference of arrays with additional index check
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * The array to compare from.
     * </p>
     * @param array<int|string, mixed> ...$excludes [optional] <p>
     * An array to compare against.
     * </p>
     *
     * @return array<int|string, mixed> An array containing all the entries from array1 that are not present in any of the other arrays.
     */
    public static function differenceAssoc (array $array, array ...$excludes):array {

        return array_diff_assoc($array, ...$excludes);

    }

    /**
     * ### Removes duplicate values from an array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * The array to remove duplicates.
     * </p>
     *
     * @return array<int|string, mixed> The filtered array.
     */
    public static function unique (array $array):array {

        return array_unique($array);

    }

    /**
     * ### Removes unique values from an array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * The array to remove unique values.
     * </p>
     *
     * @return array<int|string, mixed> An array containing all the entries from array1 that are not present in any of the other arrays.
     */
    public static function duplicates (array $array):array {

        return self::differenceAssoc($array, self::unique($array));

    }

    /**
     * ### Exchanges all keys with their associated values in array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * The array to flip.
     * </p>
     *
     * @throws Error If method flip requires that all values be either int or string.
     *
     * @return array<int|string, mixed> The flipped array.
     */
    public static function flip (array $array):array {

        foreach ($array as $key => $value) {

            is_int($value) || is_string($value) ?: throw new Error('Method flip requires that all values be either int or string');

            $items[$value] = $key;

        }

        return $items ?? [];

    }

    /**
     * ### Get all items from array with the specified keys
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * The array to filter items.
     * </p>
     * @param array<int|string, mixed> $keys <p>
     * List of keys to return.
     * </p>
     *
     * @throws Error If method flip requires that all values be either int or string.
     *
     * @return array<int|string, mixed> The filtered array.
     */
    public static function only (array $array, array $keys):array {

        return self::intersectKey($array, self::flip($keys));

    }

    /**
     * ### Get all items from array except for those with the specified keys
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * The array to filter items.
     * </p>
     * @param array<int|string, mixed> $keys <p>
     * List of keys to return.
     * </p>
     *
     * @throws Error If method flip requires that all values be either int or string.
     *
     * @return array<int|string, mixed> The filtered array.
     */
    public static function except (array $array, array $keys):array {

        return self::differenceKey($array, self::flip($keys));

    }

    /**
     * ### Pad array to the specified length with a value
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * Initial array of values to pad.
     * </p>
     * @param int $size <p>
     * New size of the array.
     * </p>
     * @param mixed $value <p>
     * Value to pad if input is less than pad_size.
     * </p>
     *
     * @return array<int|string, mixed> A copy of the input padded to size specified by pad_size with value pad_value. If pad_size is positive then the array is padded on the right, if it's negative then on the left. If the absolute value of pad_size is less than or equal to the length of the input then no padding takes place.
     */
    public static function pad (array $array, int $size, mixed $value):array {

        return array_pad($array, $size, $value);

    }

    /**
     * ### Pick one or more random values out of the array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * Array from we are picking random items.
     * </p>
     * @param int $number [optional] <p>
     * Specifies how many entries you want to pick.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * Whether you want to preserve keys from original array or not.
     * </p>
     *
     * @throws Error If asked number of items is greater than total number of items in array.
     *
     * @return mixed If you are picking only one entry, returns the key for a random entry. Otherwise, it returns an array of keys for the random entries.
     */
    public static function random (array $array, int $number = 1, bool $preserve_keys = false):mixed {

        // check if asked number of items is greater than total number of items in array
        !($number > self::count($array)) ?: throw new Error(sprintf('Asked random values are %d, and are greater then total number of items in array %d.', $number, self::count($array)));

        // get the random keys from array items
        $keys = array_rand($array, $number);

        // if keys are not array
        if (!self::isArray($keys)) {

            /**
             * PHPStan stan reports that keys might not be an array, but with method isArray it is already checked.
             * @phpstan-ignore-next-line
             */
            return $array[$keys];

        }

        if ($preserve_keys) { // if we turn on preserved key

            /**
             * PHPStan stan reports that keys might not be an array, but with method isArray it is already checked.
             * @phpstan-ignore-next-line
             */
            foreach ($keys as $key) {

                $items[$key] = $array[$key];

            }

        } else { // if we turn off preserved key

            /**
             * PHPStan stan reports that keys might not be an array, but with method isArray it is already checked.
             * @phpstan-ignore-next-line
             */
            foreach ($keys as $key) {

                $items[] = $array[$key];

            }

        }

        return $items ?? [];

    }

    /**
     * ### Reverse the order of array items
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * Array to reverse.
     * </p>
     * @param bool $preserve_keys [optional] <p>
     * Whether you want to preserve keys from original array or not.
     * </p>
     *
     * @return array<int|string, mixed> The filtered array.
     */
    public static function reverse (array $array, bool $preserve_keys = false):array {

        return array_reverse($array, $preserve_keys);

    }

    /**
     * ### Computes the intersection of arrays
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * The array with main values to check.
     * </p>
     * @param array<int|string, mixed> ...$arrays <p>
     * An array to compare values against.
     * </p>
     *
     * @return array<int|string, mixed> The filtered array.
     */
    public static function intersect (array $array, array ...$arrays):array {

        return array_intersect($array, ...$arrays);

    }

    /**
     * ### Computes the intersection of arrays using keys for comparison
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * The array with main values to check.
     * </p>
     * @param array<int|string, mixed> ...$arrays <p>
     * An array to compare values against.
     * </p>
     *
     * @return array<int|string, mixed> The filtered array.
     */
    public static function intersectKey (array $array, array ...$arrays):array {

        return array_intersect_key($array, ...$arrays);

    }

    /**
     * ### Computes the intersection of arrays with additional index check
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * The array with main values to check.
     * </p>
     * @param array<int|string, mixed> ...$arrays <p>
     * An array to compare values against.
     * </p>
     *
     * @return array<int|string, mixed> The filtered array.
     */
    public static function intersectAssoc (array $array, array ...$arrays):array {

        return array_intersect_assoc($array, ...$arrays);

    }

    /**
     * ### Return all the keys or a subset of the keys of an array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * An array containing keys to return.
     * </p>
     * @param mixed $filter [optional] <p>
     * If specified, then only keys containing these values are returned.
     * </p>
     *
     * @return array<int, int|string> An array of all the keys in input.
     */
    public static function keys (array $array, mixed $filter = null) {

        return is_null($filter) ? array_keys($array) : array_keys($array, $filter, true);

    }

    /**
     * ### Counts all elements in an array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param array<int|string, mixed> $array <p>
     * The array to iterate over.
     * </p>
     * @param null|callable $callback [optional] <p>
     * The callback function to use.
     * If no callback is supplied, all entries of input equal to false (see converting to boolean) will be removed.
     * </p>
     * @param bool $pass_key [optional] <p>
     * Pass key as the argument to callback.
     * </p>
     * @param bool $pass_value [optional] <p>
     * Pass value as the argument to callback.
     * </p>
     *
     * @return array<int|string, mixed> Filtered array.
     */
    public static function filter (array $array, ?callable $callback = null, bool $pass_key = false, bool $pass_value = true):array {

        $mode = $pass_key && $pass_value
            ? ARRAY_FILTER_USE_BOTH
            : ($pass_key
                ? ARRAY_FILTER_USE_KEY
                : 0);

        /**
         * PHPStan stan reports that callback cannot be null.
         * @phpstan-ignore-next-line
         */
        return array_filter($array, $callback, $mode);

    }

    /**
     * ### Counts all elements in an array
     * @since 0.2.1.pre-alpha.M2
     *
     * @param string|int|float $start <p>
     * First value of the sequence.
     * </p>
     * @param string|int|float $end <p>
     * The sequence is ended upon reaching the end value.
     * </p>
     * @param int|float $step [optional] <p>
     * If a step value is given, it will be used as the increment between elements in the sequence.
     * Step should be given as a positive number. If not specified, step will default to 1.
     * </p>
     *
     * @throws Error If Your start is bigger then the end of array.
     * @throws Error If Your step is bigger then the end of array.
     *
     * @return array<int, mixed> An array of elements from start to end, inclusive.
     */
    public static function range (string|int|float $start, string|int|float $end, int|float $step = 1):array {

        try {

            return range($start, $end, $step);

        } catch (Throwable $error) {

            if ($start > $end) throw new Error(sprintf('Your start %d is bigger then the end of array %d.', $start, $end));

            if ($end < $step) throw new Error(sprintf('Your step %d is bigger then the end of array %d.', $end, $step));

            throw new Error($error->getMessage());

        }

    }

}