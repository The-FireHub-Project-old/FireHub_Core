<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package.
 * @since 0.1.6.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license OSL Open Source License version 3 - [https://opensource.org/licenses/OSL-3.0](https://opensource.org/licenses/OSL-3.0)
 *
 * @package FireHub\Initializers
 * @version 1.0
 */

namespace FireHub\Initializers;

/**
 * ### Abstract Base Kernel
 *
 * Process requests that come in through various sources
 * and give client appropriate response.
 * @since 0.1.6.pre-alpha.M1
 *
 * @package FireHub\Initializers
 */
abstract class Kernel {

    /**
     * ### Handle client runtime
     * @since 0.1.6.pre-alpha.M1
     *
     * @return string
     */
    abstract public function runtime ():string;

}