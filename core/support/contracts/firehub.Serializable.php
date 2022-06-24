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

namespace FireHub\Support\Contracts;

use JsonSerializable;

/**
 * ### Serializable contract
 *
 * Contract allow serialization for objects.
 * @since 0.2.0.pre-alpha.M2
 *
 * @package FireHub\Support\Contracts
 */
interface Serializable extends JsonSerializable {

    /**
     * ### Generates a storable representation of the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return string String containing a byte-stream representation of value that can be stored anywhere.
     */
    public function serialize ():string;

    /**
     * ### Generates a JSON representation of the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return string|false JSON encoded string on success or FALSE on failure.
     */
    public function toJSON ():string|false;

    /**
     * ### Generates a storable representation of the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @return array<int|string, mixed> An associative array of key/value pairs that represent the serialized form of the object.
     */
    public function __serialize ():array;

    /**
     * ### Converts from serialized data back to the collection
     * @since 0.2.0.pre-alpha.M2
     *
     * @param array<int|string, mixed> $data <p>
     * Serialized data.
     * </p>
     *
     * @return void
     */
    public function __unserialize (array $data):void;

}