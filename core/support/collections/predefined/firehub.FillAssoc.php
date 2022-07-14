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
use FireHub\Support\Collections\Types\Array_Type;
use Error;

/**
 * ### Fill the collection with keys and values
 * @since 0.2.0.pre-alpha.M2
 *
 * @package FireHub\Support\Collections
 */
final class FillAssoc {

    /**
     * ### Constructor
     * @since 0.2.0.pre-alpha.M2
     *
     * @param array<int|string, mixed> $keys <p>
     * Array of values that will be used as keys.
     * Illegal values for key will be converted to string.
     * <p>
     * @param array<int|string, mixed> $values <p>
     * Values to use for filling.
     * </p>
     */
    public function __construct (private array $keys, private array $values) {}

    /**
     * ### Fill as Basic Collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @throws Error If one of the original key is neither string nor integer.
     * @throws Error If current and combined collection need to have the same number of items.
     *
     * @return \FireHub\Support\Collections\Types\Array_Type
     */
    public function asBasic ():Array_Type {

        return Collection::create(fn():array => $this->keys)->combine($this->values);

    }

}