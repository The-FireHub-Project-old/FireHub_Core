<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package.
 * @since 0.1.4.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license OSL Open Source License version 3 - [https://opensource.org/licenses/OSL-3.0](https://opensource.org/licenses/OSL-3.0)
 *
 * @package FireHub
 *
 * @version 1.0
 * @version 1.1 Added autoload method and DS and FIREHUB_ROOT import constants.
 * @version 1.2 Added processKernel boot sequence and processKernel method.
 */

namespace FireHub;

use FireHub\Initializers\Enums\Kernel as Kernel_Enum;
use FireHub\Initializers\Kernel;
use DirectoryIterator, Error;

use const FireHub\Initializers\Constants\DS;
use const FireHub\Initializers\Constants\FIREHUB_ROOT;

/**
 * ### Main FireHub class for bootstrapping
 *
 * This class contains all system definitions, constants and dependant
 * components for FireHub bootstrapping.
 * @since 0.1.4.pre-alpha.M1
 *
 * @package FireHub
 */
final class FireHub {

    /**
     * ### Light the torch
     *
     * This methode serves for instantiating FireHub framework
     * and is the only publicly available method.
     *
     * @since 0.1.4.pre-alpha.M1
     * @since 0.1.6.pre-alpha.M1 Added processKernel boot sequence.
     *
     * @param \FireHub\Initializers\Enums\Kernel $kernel <p>
     * Pick Kernel from Kernel enum, process your
     * request and return appropriate response.
     * </p>
     *
     * @return string
     */
    public function boot (Kernel_Enum $kernel):string {

        return $this
            ->bootloaders()
            ->processKernel($kernel->kernel());

    }

    /**
     * ### Initialize bootloaders
     *
     * Load series of bootloaders required for
     * booting FireHub framework.
     *
     * @since 0.1.4.pre-alpha.M1
     * @since 0.1.5.pre-alpha.M1 Added autoload bootloader.
     *
     * @return $this This object.
     */
    private function bootloaders ():self {

        return $this
            ->registerConstants()
            ->autoload();

    }

    /**
     * ### Register init constants
     *
     * This method will scan FireHub\Initializers\Constants folder
     * and automatically include all PHP files.
     * @since 0.1.4.pre-alpha.M1
     *
     * @return $this This object.
     */
    private function registerConstants ():self {

        foreach (new DirectoryIterator(__DIR__.'/initializers/constants/') as $file) {

            if ($file->isFile() && $file->getExtension() === 'php') {

                require $file->getPathname();

            }

        }

        return $this;

    }

    /**
     * ### Load autoload file
     *
     * This file contains definitions and series of functions
     * needed for calling objects.
     * @since 0.1.5.pre-alpha.M1
     *
     * @throws Error if system cannot load Autoload file.
     *
     * @return $this This object.
     */
    private function autoload ():self {

        if (!include(FIREHUB_ROOT.DS.'initializers'.DS.'firehub.Autoload.php')) {

            throw new Error('Cannot load Autoload file, please contact your administrator.');

        }

        return $this;

    }

    /**
     * ### Response
     * @since 0.1.6.pre-alpha.M1
     *
     * @param \FireHub\Initializers\Kernel $kernel <p>
     * Picked Kernel from Kernel enum, process your
     * request and return appropriate response.
     * </p>
     *
     * @return string Response from Kernel.
     */
    private function processKernel (Kernel $kernel):string {

        // handle client runtime
        return $kernel->runtime();

    }

}