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
 * @version 1.0
 */

namespace FireHub;

use DirectoryIterator;

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
     * @since 0.1.4.pre-alpha.M1
     *
     * @return $this This object.
     */
    public function boot ():self {

        return $this
            ->bootloaders();

    }

    /**
     * ### Initialize bootloaders
     *
     * Load series of bootloaders required for
     * booting FireHub framework.
     * @since 0.1.4.pre-alpha.M1
     *
     * @return $this This object.
     */
    private function bootloaders ():self {

        return $this
            ->registerConstants();

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

}