<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package.
 *
 * This file contains definitions and series of functions needed for calling objects.
 * File should return true if class is correctly loaded or false if error occurs.
 * @since 0.1.5.pre-alpha.M1
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license OSL Open Source License version 3 - [https://opensource.org/licenses/OSL-3.0](https://opensource.org/licenses/OSL-3.0)
 *
 * @package FireHub\Initializers
 *
 * @version 1.0
 * @version 1.1 Callback function now checks if object is empty before requiring it.
 */

namespace FireHub\Initializers;

use FireHub\Initializers\Enums\ {
    Prefix, Suffix
};
use Error;

use const FireHub\Initializers\Constants\DS;
use const FireHub\Initializers\Constants\FIREHUB_ROOT;
use const FireHub\Initializers\Constants\CORE_ROOT;
use const FireHub\Initializers\Constants\PACKAGES_ROOT;
use const FireHub\Initializers\Constants\APP_ROOT;

use function explode;
use function count;
use function strtolower;
use function implode;
use function array_shift;
use function is_file;
use function sprintf;
use function reset;
use function end;
use function array_pop;
use function spl_autoload_register;

require_once FIREHUB_ROOT.DS.'initializers/enums/firehub.Prefix.php';
require_once FIREHUB_ROOT.DS.'initializers/enums/firehub.Suffix.php';

/**
 * ### Extract object name and file type based on it's full name
 * @since 0.1.5.pre-alpha.M1
 *
 * @param string $fullName <p>
 * Object full name.
 * </p>
 *
 * @return string[] Filename components.
 */
$fileComponents = static fn(string $fullName):array => explode('_', $fullName);

/**
 * ### Object name is always first component
 * @since 0.1.5.pre-alpha.M1
 *
 * @param string $fullName <p>
 * Object full name.
 * </p>
 *
 * @return string Object name.
 */
$getName = static function (string $fullName) use ($fileComponents):string {

    // extract object name and file type based on it's full name
    $file_components = $fileComponents($fullName);

    // object name is always first component
    return $file_components[0] ?? '';

};

/**
 * ### Object type is second component if exists, otherwise type is false
 * @since 0.1.5.pre-alpha.M1
 *
 * @param string $fullName <p>
 * Object full name.
 * </p>
 *
 * @throws Error If type could not be loaded or has problem with suffix.
 *
 * @return string|false Object type or false if it is not set.
 */
$getType = static function (string $fullName) use ($fileComponents):string|false {

    // extract object name and file type based on it's full name
    $file_components = $fileComponents($fullName);

    $type = count($file_components) > 1 ? strtolower($file_components[1]) : false;

    // check suffix
    if ($type && (!Suffix::tryFrom($type))) {

        throw new Error(sprintf('Object %s could not be loaded. There could be the problem suffix: %s', $fullName,  $type));

    }

    return $type;

};

/**
 * ### Get entire namespace as path
 * @since 0.1.5.pre-alpha.M1
 *
 * @param string[] $namespace_components <p>
 * Object namespace path as array.
 * </p>
 *
 * @return string Object path in lowercase.
 */
$getNamespacePath = static fn(array $namespace_components):string => strtolower(implode(DS, $namespace_components));

/**
 * ### Firehub files
 *
 * Firehub files must contain prefix from Prefix enum in their name,
 * and if they have been suffixed - it has to be from enum FILE_TYPES.
 * @since 0.1.5.pre-alpha.M1
 *
 * @param string[] $namespace_components <p>
 * Object namespace path as array.
 * </p>
 * @param string $fullName <p>
 * Object full name.
 * </p>
 *
 * @throws Error If file doesn't exist.
 *
 * @return string Object real full path.
 */
$firehubFile = static function (array $namespace_components, string $fullName) use ($getName, $getType, $getNamespacePath):string {

    // object name is always first component
    $name = $getName($fullName);

    // object type is second component if exists, otherwise type is false
    $type = $getType($fullName);

    // check prefix and if type exists put dot between name and extension
    $object_real_name = $type ? Prefix::FIREHUB->value.'.'.$name.'.'.$type : Prefix::FIREHUB->value.'.'.$name;

    // remove first component, e.g. root namespace
    array_shift($namespace_components);

    // get entire namespace as path
    $path = !empty($getNamespacePath($namespace_components)) ? $getNamespacePath($namespace_components).DS : '';

    // rule for tests
    if (isset($namespace_components[0]) && $namespace_components[0] === 'Tests') {

        return !is_file($file = CORE_ROOT.DS.$path.DS.$object_real_name.'.php') ? throw new Error(sprintf("File: %s doesn't exist", $file)) : $file;

    }

    // rule for packages
    if (isset($namespace_components[0]) && $namespace_components[0] === 'Packages') {

        return !is_file($file = PACKAGES_ROOT.DS.strtolower($namespace_components[1]).DS.strtolower($namespace_components[1]).'.phar'.DS.$object_real_name.'.php') ? throw new Error(sprintf("File: %s doesn't exist", $file)) : $file;

    }

    // files must come from FireHub phar core library
    return !is_file($file = FIREHUB_ROOT.DS.$path.$object_real_name.'.php') ? throw new Error(sprintf("File: %s doesn't exist", $file)) : $file;

};

/**
 * ### Application files
 * @since 0.1.5.pre-alpha.M1
 *
 * @param string[] $namespace_components <p>
 * Object namespace path as array.
 * </p>
 * @param string $fullName <p>
 * Object full name.
 * </p>
 *
 * @throws Error If file doesn't exist.
 *
 * @return string Object real full path.
 */
$appFile = static function (array $namespace_components, string $fullName) use ($getName, $getType, $getNamespacePath):string {

    // object name is always first component
    $name = $getName($fullName);

    // object type is second component if exists, otherwise type is false
    $type = $getType($fullName);

    // check prefix and if type exists put dot between name and extension
    $object_real_name = $type ? $name.'.'.$type : $name;

    // get entire namespace as path
    $path = $getNamespacePath($namespace_components);

    if (!is_file($file = APP_ROOT.DS.$path.DS.$object_real_name.'.php')) {

        throw new Error(sprintf("File: %s doesn't exist", $file));

    }

    // files must come from App root folder
    return $file;

};

/**
 * ### Vendor (external) files
 *
 * All external files need to be from "vendor" folder,
 * and don't need any extra filters during autoload process.
 * @since 0.1.5.pre-alpha.M1
 *
 * @param string[] $namespace_components <p>
 * Object namespace path as array.
 * </p>
 * @param string $fullName <p>
 * Object full name.
 * </p>
 *
 * @return string Object real full path.
 */
$vendorFile = static function (array $namespace_components, string $fullName) use ($getNamespacePath):string {

    // get entire namespace as path
    $path = $getNamespacePath($namespace_components);

    if (is_file($file = APP_ROOT.DS.'vendor'.DS.$path.DS.$fullName.'.php')) {

        // get entire namespace as path
        return $file;

    }

    return '';

};

/**
 * ### Search file form object FQN
 * @since 0.1.5.pre-alpha.M1
 *
 * @param string $object_FQN <p>
 * Calling object fully qualified name.
 * </p>
 *
 * @return string Object real full path.
 */
$file = static function (string $object_FQN) use ($firehubFile, $appFile, $vendorFile):string {

    // object components
    $object_FQN_components = explode('\\', $object_FQN);
    $first_component = reset($object_FQN_components); // first component, represents root object namespace
    $last_component = end($object_FQN_components); // last component, represents object name

    /*
     * Now that we have boot root namespace and object name,
     * we can pop the object name from components, so now
     * variable will represent object path
     */
    array_pop($object_FQN_components);

    // let's try to match object real path based on first component
    return match ($first_component) {
        'FireHub' => $firehubFile($object_FQN_components, $last_component),
        'App' => $appFile($object_FQN_components, $last_component),
        default => $vendorFile($object_FQN_components, $last_component)
    };

};

/**
 * ### Autoload function for object registration
 * @since 0.1.5.pre-alpha.M1
 * @since 0.1.6.pre-alpha.M1 Checks if object is empty before requiring it.
 *
 * @param string $object_FQN <p>
 * Calling object fully qualified name.
 * </p>
 *
 * @return void
 */
$callback = static function (string $object_FQN) use ($file):void {

    empty($file($object_FQN)) ?: require $file($object_FQN); // check if object is empty, then include object file

};

/**
 * ### Autoload for called objects
 * @since 0.1.5.pre-alpha.M1
 *
 * @param callable $callback <p>
 * Autoload callback function.
 * </p>
 * @param bool $throw <p>
 * Throws Exception when the callback cannot be registered.
 * </p>
 * @param bool $prepend <p>
 * If true, function will prepend the autoloader on the autoload
 * queue instead of appending it.
 * </p>
 *
 * @return bool True if object is registered, false otherwise.
 */
return spl_autoload_register (callback: $callback, throw: true, prepend: false);