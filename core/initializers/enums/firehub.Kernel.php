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
use FireHub\Kernel\ {
    HTTP\Kernel as HTTP_Kernel,
    HTTP\Micro\Kernel as HTTP_Micro_Kernel,
    Console\Kernel as Console_Kernel,
    Swoole\Kernel as Swoole_Kernel
};

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
     * Simplified Micro HTTP Kernel
     */
    case MICRO_HTTP;

    /**
     * Console Kernel
     */
    case CONSOLE;

    /**
     * Swoole Kernel
     */
    case SWOOLE;

    /**
     * ### Get selected Kernel
     * @since 0.1.6.pre-alpha.M1
     *
     * @return \FireHub\Initializers\Kernel Instantiate selected Kernel.
     */
    public function kernel ():Base_Kernel {

        return match ($this) {
            self::HTTP => new HTTP_Kernel(),
            self::MICRO_HTTP => new HTTP_Micro_Kernel(),
            self::CONSOLE => new Console_Kernel,
            self::SWOOLE => new Swoole_Kernel
        };

    }

}