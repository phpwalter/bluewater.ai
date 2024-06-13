<?php

/**
 * Configuration Class to process Bluewater 8 MVC Core config file
 * and an application level config file.
 *
 * This file is part of Bluewater 8 MVC.<br />
 * <i>Copyright (c) 2006 - 2021 Walter Torres <walter@torres.ws></i>
 *
 * <b>NOTICE OF LICENSE</b><br />
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at:
 * {@link http://opensource.org/licenses/osl-3.0.php}.<br />
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@bluewatermvc.org so one can be sent to you immediately.
 *
 * <b>DISCLAIMER</b><br />
 * Do not modify to this file if you wish to upgrade Bluewater 8 MVC
 * in the future. If you wish to customize Bluewater 8 MVC for your needs
 * please refer to {@link http://web.bluewatermvc.org} for more information.
 *
 * PHP version 7+
 *
 * @package     Bluewater8_Core
 * @subpackage  Helper
 * @link        http://web.bluewatermvc.org
 *
 * @author      Walter Torres <walter@torres.ws>
 * @version     v.8.0 (11/02/2021)
 *
 * @copyright   Copyright (c) 2006 - 2021 Walter Torres <walter@torres.ws>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @filesource
 *
 */

declare(strict_types=1);

namespace Bluewater\Helper;

use Bluewater\Traits\Helper;

use function array_keys;
use function array_merge;
use function array_search;
use function array_splice;
use function is_int;

/**
 * Helper class to recursively merges arrays.
 *
 * If one of the Arguments isn't an Array, first Argument is returned.
 * If an Element is an Array in both Arrays, Arrays are merged recursively,
 * otherwise the element in $ins will overwrite the element in $arr
 * (regardless if key is numeric or not). This also applies to Arrays in
 * $arr, if the Element is scalar in $ins (in difference to the previous
 * approach).
 *
 * @package     Bluewater_Core
 * @subpackage  Helper
 *
 * @example /examples
 *
 * @author Walter Torres <walter@torres.ws>
 * @version $Revision: 1.0 $
 *
 */
class Safe_Unserialize
{

// ==========================================================
// Class Traits
    use Helper;

// ==========================================================
// Class Constants

// ==========================================================
// Class Properties

// ==========================================================
// Class Methods

    /**
     * Recursively merges arrays
     *
     * Setup inbound parameters for actual method call.
     *
     * Method arguments are actually sent within an indexed array.
     *
     * @param array|null $_args Method arguments are sent within an indexed array
     * @return array $arr Merges arrays
     * @uses classmethod Helper_array_insert::_array_insert()
     *
     * @access public
     *
     */
    final public function helper(array $_args = null): array
    {
        // Array to merge into
        /** @var array $target */
        $target = $_args[0] ?? [];

        // Array to merge from
        /** @var array $source */
        $source = $_args[1] ?? [];

        // Send off to recursive method
        return $this->_array_insert($source, $target);
    }

    /**
     * Recursively merges arrays
     *
     * This method does the actual work.
     *
     * @param array|null $target Array to merge into
     * @param mixed $insert data to merge into $source array
     * @param int|null $position where to put source data
     * @return array $target Merges arrays
     * @author Halil Özgür
     * @link https://stackoverflow.com/questions/3797239/insert-new-item-in-array-on-any-position-in-php
     *
     * @uses self::__array_insert()
     *
     * @access private
     */
    private function _array_insert(array $target = null, array $insert = null, int $position = null): array
    {
        if (is_int($position)) {
            array_splice($target, $position, 0, $insert);
        } else {
            $pos = array_search($position, array_keys($target));
            $array = array_merge(
                array_slice($target, 0, $pos),
                $insert,
                array_slice($target, $pos)
            );
        }

        return $target;
    }

}

// eof
