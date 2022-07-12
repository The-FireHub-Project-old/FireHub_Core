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
    Array_Type, Index_Type, Lazy_Type, Object_Type
};
use Generator;

use function array_fill;

/**
 * ### Fill the collection with values
 * @since 0.2.0.pre-alpha.M2
 *
 * @package FireHub\Support\Collections
 */
final class Fill {

    /**
     * ### Constructor
     * @since 0.2.0.pre-alpha.M2
     *
     * @param mixed $value <p>
     * Value to use for filling.
     * </p>
     * @param int $length <p>
     * Number of elements to insert.
     * </p>
     */
    public function __construct (private mixed $value, private int $length) {}

    /**
     * ### Fill as Basic Collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return \FireHub\Support\Collections\Types\Array_Type
     */
    public function asBasic ():Array_Type {

        return Collection::create(fn():array => array_fill(0, $this->length, $this->value));

    }

    /**
     * ### Fill as Index Collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return \FireHub\Support\Collections\Types\Index_Type
     */
    public function asIndex ():Index_Type {

        return Collection::index(function ($items):void {
            for ($counter = 0; $counter < $this->length; $counter++) {
                $items[$counter] = $this->value;
            }
        }, $this->length);

    }

    /**
     * ### Fill as Lazy Collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return \FireHub\Support\Collections\Types\Lazy_Type
     */
    public function asLazy ():Lazy_Type {

        return Collection::lazy(function ():Generator {
            for ($counter = 0; $counter < $this->length; $counter++) {
                yield $this->value;
            }
        });

    }

    /**
     * ### Fill as Object Collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return \FireHub\Support\Collections\Types\Object_Type
     */
    public function asObject ():Object_Type {

        return Collection::object(function ($items):void {
            for ($counter = 0; $counter < $this->length; $counter++) {
                $items[new $this->value] = $counter;
            }
        });

    }

}