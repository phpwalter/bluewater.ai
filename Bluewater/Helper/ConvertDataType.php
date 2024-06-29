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

use Exception;

use function ctype_alpha;
use function ctype_alnum;

use function intval;
use function strtolower;
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
class ConvertDataType
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
            $_args[0] = $this->convertData($_args[0]);
        }

        // Send off to recursive method
        return $_args[0];
    }


    /**
     * This class is responsible for converting string data types into their
     * appropriate PHP values. The class address the common requirement in
     * configuration and other similar scenarios where the interpretation of
     * string values as specific data types (such as boolean, null, or numerical
     * values) is necessary.
     *
     * The following conversions are performed:
     * - 't', 'true' strings to boolean `true`
     * - 'f', 'false' strings to boolean `false`
     * - 'null' string to `null`
     * - Numeric strings to their corresponding integer or float values
     *
     * In the absence of a matching pattern, the input string is returned
     * unchanged. Each possible conversion is attempted in the order of
     * predefined, integer, then float values, and the first successful
     * conversion is returned immediately, optimizing for performance.
     *
     * @param mixed $_var Variable to type
     * @return integer $_type  Variable Type (based on constants as defined in bluewater.ini.php)
     *
     * @return mixed
     * @throws Exception
     * @author   son9ne dot junk at gmail dot com
     * @link     http://www.php.net/manual/en/function.is-string.php#96392
     *
     * @access private
     *
     * The main conversion method.
     * Tries predefined, int, and float conversion in order.
     * Returns the first successful conversion, or original value.
     *
     */
    private function convertData($value): mixed
    {
        $converters = [
            'convertToPredefined',
            'convertToInt',
            'convertToFloat'
        ];

        foreach ($converters as $converter) {
            if (!method_exists($this, $converter)) {
                throw new Exception("Invalid converter: $converter");
            }

            $newValue = self::{$converter}($value);
            if ($newValue !== $value) {
                return $newValue;
            }
        }

        return $value;
    }


    /**
     * Converts to predefine value.
     *
     * @param string $value
     * @return mixed
     */
    private static function convertToPredefined(string $value): mixed
    {
        $predefinedValues = [
            't' => true,
            'true' => true,
            'f' => false,
            'false' => false,
            'null' => null
        ];

        $lowercasedValue = strtolower($value);
        return $predefinedValues[$lowercasedValue] ?? $value;
    }

    /**
     * Converts to integer, if possible.
     *
     * @param string $value
     * @return mixed
     */
    private static function convertToInt(string $value): mixed
    {
        return ((string)(int)$value === $value) ? (int)$value : $value;
    }

    /**
     * Converts to float, if possible.
     *
     * @param string $value
     * @return mixed
     */
    private static function convertToFloat(string $value): mixed
    {
        return ((string)(float)$value === $value) ? (float)$value : $value;
    }

}


// eof
