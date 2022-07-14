<?php declare(strict_types = 1);

/**
 * This file is part of FireHub Web Application Framework package.
 * @since 0.2.0.pre-alpha.M2
 *
 * @author Danijel Galić
 * @copyright 2022 FireHub Web Application Framework
 * @license OSL Open Source License version 3 - [https://opensource.org/licenses/OSL-3.0](https://opensource.org/licenses/OSL-3.0)
 *
 * @package FireHub\Support\Collections
 * @version 1.0
 */

namespace FireHub\Support\Collections\Enums;

/**
 * ### Sorting collection enum
 * @since 0.2.0.pre-alpha.M2
 *
 * @package FireHub\Support\Collections
 */
enum SortFlag:int {

    /**
     * ### Compare items normally
     * @since 0.2.0.pre-alpha.M2
     */
    case SORT_REGULAR = 0;

    /**
     * ### Compare items numerically
     * @since 0.2.0.pre-alpha.M2
     */
    case SORT_NUMERIC = 1;

    /**
     * ### Compare items as strings
     * @since 0.2.0.pre-alpha.M2
     */
    case SORT_STRING = 2;

    /**
     * ### Compare items as strings, based on the current locale. It uses the locale, which can be changed using setlocale()
     * @since 0.2.0.pre-alpha.M2
     */
    case SORT_LOCALE_STRING = 5;

    /**
     * ### Compare items as strings using "natural ordering" like natsort()
     * @since 0.2.0.pre-alpha.M2
     */
    case SORT_NATURAL = 6;

    /**
     * ### Sort strings case-insensitively
     * @since 0.2.0.pre-alpha.M2
     */
    case SORT_STRING_FLAG_CASE = 10;

    /**
     * ### Sort natural case-insensitively
     * @since 0.2.0.pre-alpha.M2
     */
    case SORT_NATURAL_FLAG_CASE = 14;

}