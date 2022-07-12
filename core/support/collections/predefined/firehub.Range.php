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
    Array_Type, Index_Type, Lazy_Type
};
use Error, Generator;

use function range;
use function count;

/**
 * ### Creates the collection containing a range of items
 * @since 0.2.0.pre-alpha.M2
 *
 * @package FireHub\Support\Collections
 */
final class Range {

    /**
     * ### Constructor
     * @since 0.2.0.pre-alpha.M2
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
     * @throws Error If Your start is bigger then the end of collection.
     * @throws Error If Your step is bigger then the end of collection.
     */
    public function __construct (private string|int|float $start, private string|int|float $end, private int|float $step = 1) {

        if ($this->start > $this->end) throw new Error(sprintf('Your start %d is bigger then the end of collection %d.', $this->start, $this->end));

        if ($this->end < $this->step) throw new Error(sprintf('Your step %d is bigger then the end of collection %d.', $this->step, $this->end));

    }

    /**
     * ### Range as Basic Collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return \FireHub\Support\Collections\Types\Array_Type
     */
    public function asBasic ():Array_Type {

        return Collection::create(fn():array => range($this->start, $this->end, $this->step));

    }

    /**
     * ### Range as Index Collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return \FireHub\Support\Collections\Types\Index_Type
     */
    public function asIndex ():Index_Type {

        return Collection::index(function ($items):void {
            $counter = 0;
            foreach (range($this->start, $this->end, $this->step) as $value) {
                $items[$counter++] = $value;
            }
        }, count(range($this->start, $this->end, $this->step)));

    }

    /**
     * ### Range as Lazy Collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return \FireHub\Support\Collections\Types\Lazy_Type
     */
    public function asLazy ():Lazy_Type {

        return Collection::lazy(function ():Generator {
            foreach (range($this->start, $this->end, $this->step) as $value) {
                yield $value;
            }
        });

    }

}