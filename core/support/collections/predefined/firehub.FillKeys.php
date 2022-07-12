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

namespace FireHub\Support\Collections\Predefined;

use FireHub\Support\Collections\Collection;
use FireHub\Support\Collections\Types\ {
    Array_Type, Lazy_Type
};
use Generator;

use function array_fill_keys;

/**
 * ### Fill an array with values, specifying keys
 * @since 0.2.0.pre-alpha.M2
 *
 * @package FireHub\Support\Collections
 */
final class FillKeys {

    /**
     * ### Constructor
     * @since 0.2.0.pre-alpha.M2
     *
     * @param array<int|string, int|string> $keys <p>
     * Array of values that will be used as keys.
     * Illegal values for key will be converted to string.
     * </p>
     * @param mixed $value <p>
     * Value to use for filling.
     * </p>
     */
    public function __construct (private array $keys, private mixed $value) {}

    /**
     * ### Fill as Basic Collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return \FireHub\Support\Collections\Types\Array_Type
     */
    public function asBasic ():Array_Type {

        return Collection::create(fn():array => array_fill_keys($this->keys, $this->value));

    }


    /**
     * ### Fill as Lazy Collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return \FireHub\Support\Collections\Types\Lazy_Type
     */
    public function asLazy ():Lazy_Type {

        return Collection::lazy(function ():Generator {
            foreach ($this->keys as $key) {
                yield $key => $this->value;
            }
        });

    }

}