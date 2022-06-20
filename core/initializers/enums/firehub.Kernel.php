<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package.
 * @since 0.1.6.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2020 FireHub Web Application Framework
 * @license OSL Open Source License version 3 - [https://opensource.org/licenses/OSL-3.0](https://opensource.org/licenses/OSL-3.09
 *
 * @version 1.0
 * @package FireHub\Initializers
 */

namespace FireHub\Initializers\Enums;

use FireHub\Initializers\Kernel as Base_Kernel;
use FireHub\Kernel\HTTP\Kernel as HTTP_Kernel;

/**
 * ### Enum for available Kernel types
 * @since 0.1.6.pre-alpha.M1
 *
 * @package FireHub\Initializers
 */
enum Kernel {

    /**
     * Fully functional HTTP Kernel
     */
    case HTTP;

    /**
     * ### Get selected Kernel
     * @since 0.1.6.pre-alpha.M1
     *
     * @return \FireHub\Initializers\Kernel Instantiate selected Kernel.
     */
    public function kernel ():Base_Kernel {

        return match ($this) {
            self::HTTP => new HTTP_Kernel
        };

    }

}