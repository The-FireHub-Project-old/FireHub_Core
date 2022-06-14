<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package.
 *
 * This file contains all system path constants required for
 * successful app booting. This file is required to be loaded.
 * @since 0.1.4.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license OSL Open Source License version 3 - [https://opensource.org/licenses/OSL-3.0](https://opensource.org/licenses/OSL-3.0)
 *
 * @package FireHub\Initializers
 *
 * @version 1.0
 * @version 1.1 Added CORE_ROOT constant.
 */

namespace FireHub\Initializers\Constants;

use Phar;

use const DIRECTORY_SEPARATOR;

use function defined;
use function define;
use function dirname;

/**
 * ### System definition for separating folders
 *
 * Convert "\" and "/" for different operating systems.
 * @since 0.1.4.pre-alpha.M1
 *
 * @name string
 */
defined('FireHub\Initializers\Constants\DS') ?: define('FireHub\Initializers\Constants\DS', DIRECTORY_SEPARATOR);

/**
 * ### Root FireHub path
 * @since 0.1.4.pre-alpha.M1
 *
 * @name string
 */
defined('FireHub\Initializers\Constants\FIREHUB_ROOT') ?: define('FireHub\Initializers\Constants\FIREHUB_ROOT', Phar::running(true));

/**
 * ### Core path, for paths outside phar archive
 * @since 0.1.5.pre-alpha.M1
 *
 * @name string
 */
defined('FireHub\Initializers\Constants\CORE_ROOT') ?: define('FireHub\Initializers\Constants\CORE_ROOT', dirname(Phar::running(false)).DS.'..');

/**
 * ### Packages path
 * @since 0.1.4.pre-alpha.M1
 *
 * @name string
 */
defined('FireHub\Initializers\Constants\PACKAGES_ROOT') ?: define('FireHub\Initializers\Constants\PACKAGES_ROOT', 'phar://'.dirname(Phar::running(false)).DS.'..'.DS.'packages');

/**
 * ### Root App path
 * @since 0.1.4.pre-alpha.M1
 *
 * @name string
 */
defined('FireHub\Initializers\Constants\APP_ROOT') ?: define('FireHub\Initializers\Constants\APP_ROOT', dirname(Phar::running(false)).DS.'..'.DS.'..');