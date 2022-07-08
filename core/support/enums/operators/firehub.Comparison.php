<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package.
 * @since 0.2.0.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license OSL Open Source License version 3 - [https://opensource.org/licenses/OSL-3.0](https://opensource.org/licenses/OSL-3.0)
 *
 * @package FireHub\Support\Enums
 * @version 1.0
 */

namespace FireHub\Support\Enums\Operators;

/**
 * ### Comparison operator enum
 * @since 0.2.0.pre-alpha.M2
 *
 * @package FireHub\Support\Enums
 */
enum Comparison {

    /**
     * ### Returns true if $x is equal to $y
     * @since 0.2.0.pre-alpha.M2
     */
    case EQUAL;

    /**
     * ### Returns true if $x is not equal to $y, or they are not of the same type
     * @since 0.2.0.pre-alpha.M2
     */
    case NOT_EQUAL;

    /**
     * ### Returns true if $x is greater than $y
     * @since 0.2.0.pre-alpha.M2
     */
    case GREATER;

    /**
     * ### Returns true if $x is greater than or equal to $y
     * @since 0.2.0.pre-alpha.M2
     */
    case GREATER_OR_EQUAL;

    /**
     * ### Returns true if $x is less than $y
     * @since 0.2.0.pre-alpha.M2
     */
    case LESS;

    /**
     * ### Returns true if $x is less than or equal to $y
     * @since 0.2.0.pre-alpha.M2
     */
    case LESS_OR_EQUAL;

    /**
     * ### Returns an integer less than, equal to, or greater than zero, depending on if $x is less than, equal to, or greater than $y
     * @since 0.2.0.pre-alpha.M2
     */
    case SPACESHIP;

    public function compare (mixed $a, mixed $b):bool {

        return match ($this) {
            self::EQUAL => $a === $b,
            self::NOT_EQUAL => $a !== $b,
            self::GREATER => $a > $b,
            self::GREATER_OR_EQUAL => $a >= $b,
            self::LESS => $a < $b,
            self::LESS_OR_EQUAL => $a <= $b,
            self::SPACESHIP => $a <=> $b
        };

    }

}