<?php

/**
 * Helper class to properly determine data type of a variable, since PHP.net
 * warns against using gettype()
 *
 * This file is part of Bluewater MVC.<br />
 * <i>Copyright (c) 2006 - 2011 Walter Torres <walter@torres.ws></i>
 *
 * <b>NOTICE OF LICENSE</b><br />
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at:
 * {@link http://opensource.org/licenses/osl-3.0.php}.<br />
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@bluewatermvc.org so one can sent to you immediately.
 *
 * <b>DISCLAIMER</b><br />
 * Do not modify to this file if you wish to upgrade Bluewater MVC
 * in the future. If you wish to customize Bluewater MVC for your needs
 * please refer to {@link http://web.bluewatermvc.org} for more information. *
 *
 * PHP version 5.3+
 *
 * @category    Bluewater
 * @package     Bluewater_Core
 * @subpackage  Helper
 * @link        http://web.bluewatermvc.org
 *
 * @copyright   Copyright (c) 2006 - 2011 Walter Torres <walter@torres.ws>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @filesource
 *
 */

declare(strict_types=1);

namespace Bluewater\Helper;

use function ctype_alpha;
use function ctype_alnum;

use function intval;
use function strval;
use function floatval;

use function is_array;
use function is_bool;
use function is_null;
use function is_object;
use function is_resource;
use function is_string;

/**
 * Helper class to determine data type.
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
class DataType
{

// ==========================================================
// Class Traits

// ==========================================================
// Class Constants

// ==========================================================
// Class Properties

// ==========================================================
// Class Methods

    /**
     * determine data types
     *
     * Setup commonly used types, since PHP.net warns against using gettype()
     *
     * Returns INT values based on constants as defined in bluewater.ini.php
     * which allows better data type comparisons
     *
     * @param array $_args Method arguments are sent within an indexed array
     * @return array $arr Merges arrays
     * @uses self::_data_type()
     *
     * @access public
     *
     */
    final public function helper(array $_args = array()): array|int
    {
        // Method parameters come bundled in $_args array

        // Array to trim
        if (isset($_args[0])) {
            $_args[0] = $this->evaluateType($_args[0]);
        }

        // Send off to recursive method
        return $_args[0];
    }

    /**
     * Setup commonly used types, since PHP.net warns against using gettype()
     *
     * Returns INT values based on constants as defined in bluewater.ini.php
     * which allow better data type comparisons
     *
     * @param mixed $_var Variable to type
     * @return integer $_type  Variable Type (based on constants as defined in bluewater.ini.php)
     *
     * @author   son9ne dot junk at gmail dot com
     * @link     http://www.php.net/manual/en/function.is-string.php#96392
     *
     * @access private
     *
     */
    private function evaluateType(mixed $_var): int
    {
        switch ($_var) {
            case is_null($_var):
                $_type = TYPE_NULL;
                break;

            case is_array($_var):
                $_type = TYPE_ARRAY;
                break;

            case is_bool($_var):
                $_type = TYPE_BOOLEAN;
                break;

            // Alpha, not to confused with 'string'
            case ctype_alpha($_var):
                $_type = TYPE_ALPHA;
                break;

            // Funky way to insure this is an INT, even with MINUS sign
            case ($_var == (string)(int)$_var):
                $_type = TYPE_INT;
                break;

            // Funky way to insure this is a FLOAT,even with MINUS sign
            case ($_var == (string)(float)$_var):
                $_type = TYPE_FLOAT;
                break;

            case ctype_alnum($_var):
                $_type = TYPE_ALPHANUMERIC;
                break;

            case is_string($_var):
                $_type = TYPE_STRING;
                break;

            case is_object($_var):
                $_type = TYPE_OBJECT;
                break;

            case is_resource($_var):
                $_type = TYPE_RESOURCE;
                break;

            default:
                $_type = TYPE_UNKNOWN;
                break;
        }

        return $_type;
    }

}


# eof
