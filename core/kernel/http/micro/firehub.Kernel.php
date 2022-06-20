<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package.
 * @since 0.1.6.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license OSL Open Source License version 3 - [https://opensource.org/licenses/OSL-3.0](https://opensource.org/licenses/OSL-3.0)
 *
 * @package FireHub\Kernel\HTTP
 * @version 1.0
 */

namespace FireHub\Kernel\HTTP\Micro;

use FireHub\Initializers\Kernel as BaseKernel;

/**
 * ### Micro HTTP Kernel
 *
 * Process Micro HTTP requests that come in through various sources
 * and give client appropriate response.
 * @since 0.1.6.pre-alpha.M1
 *
 * @package FireHub\Kernel\HTTP
 */
final class Kernel extends BaseKernel {

    /**
     * @inheritDoc
     */
    public function runtime ():string {

        return 'HTTP Micro Torch';

    }

}