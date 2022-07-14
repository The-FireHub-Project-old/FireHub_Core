<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package.
 * @since 0.2.0.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license OSL Open Source License version 3 - [https://opensource.org/licenses/OSL-3.0](https://opensource.org/licenses/OSL-3.0)
 *
 * @package FireHub\Support\Traits
 * @version 1.0
 */

namespace FireHub\Support\Traits;

use Closure;

/**
 * ### Tappable trait
 *
 * Allows you to "tap" into the class at a specific point and do something with the class
 * while not affecting the class itself.
 * @since 0.2.0.pre-alpha.M2
 *
 * @package FireHub\Support\Traits
 */
trait Tappable {

    /**
     * ### Tap the class
     *
     * Passes same class to the given callback, allowing you to "tap" into the class at a specific point
     * and do something with the class while not affecting the class itself.
     * @since 0.2.0.pre-alpha.M2
     *
     * @param Closure $callback <p>
     * Data from callable source.
     * </p>
     *
     * @return static This class.
     */
    public function tap (Closure $callback):static {

        $callback($this);

        return $this;

    }

}