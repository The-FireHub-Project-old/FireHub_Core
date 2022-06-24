<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package.
 * @since 0.2.0.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license OSL Open Source License version 3 - [https://opensource.org/licenses/OSL-3.0](https://opensource.org/licenses/OSL-3.0)
 *
 * @package FireHub\Support\Contracts
 * @version 1.0
 */

namespace FireHub\Support\Contracts\Iterator;

/**
 * ### NonRewindable iterator contract
 *
 * This contract doesn't allow Iterator to rewinds back to the first element of the Iterator.
 * @since 0.2.0.pre-alpha.M2
 *
 * @package FireHub\Support\Contracts
 */
interface NonRewindable extends Iterator {

}