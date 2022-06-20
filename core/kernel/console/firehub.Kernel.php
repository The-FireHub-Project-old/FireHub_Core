<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package.
 * @since 0.1.6.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license OSL Open Source License version 3 - [https://opensource.org/licenses/OSL-3.0](https://opensource.org/licenses/OSL-3.0)
 *
 * @package FireHub\Kernel\Console
 * @version 1.0
 */

namespace FireHub\Kernel\Console;

use FireHub\Initializers\Kernel as BaseKernel;

/**
 * ### Console Kernel
 *
 * Process Console requests that come in through various sources
 * and give client appropriate response.
 * @since 0.1.6.pre-alpha.M1
 *
 * @package FireHub\Kernel\Console
 */
final class Kernel extends BaseKernel {

    /**
     * @inheritDoc
     */
    public function runtime ():string {

        return 'Console Torch';

    }

}