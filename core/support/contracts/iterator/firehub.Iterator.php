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

use FireHub\Support\Contracts\Serializable;
use Countable, IteratorAggregate;

/**
 * ### Base iterator contract
 *
 * Contract contains all methods that every iterator type must have.
 * @since 0.2.0.pre-alpha.M2
 *
 * @extends IteratorAggregate<int|string, mixed>
 *
 * @package FireHub\Support\Contracts
 */
interface Iterator extends Countable, IteratorAggregate, Serializable {

}