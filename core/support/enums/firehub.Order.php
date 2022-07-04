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

namespace FireHub\Support\Enums;

/**
 * ### Ordering enum
 * @since 0.2.0.pre-alpha.M2
 *
 * @package FireHub\Support\Enums
 */
enum Order:string {

    /**
     * ### Arranging items in ascending order
     * @since 0.2.0.pre-alpha.M2
     */
    case ASC = 'ASC';

    /**
     * ### Arranging items in descending order
     * @since 0.2.0.pre-alpha.M2
     */
    case DESC = 'DESC';

}