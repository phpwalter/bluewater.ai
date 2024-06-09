<?php

/**
 * PHP Singleton Control Class
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
 * @subpackage  Bluewater_Singleton
 * @link        http://web.bluewatermvc.org
 *
 * @copyright   Copyright (c) 2006 - 2021 Walter Torres <walter@torres.ws>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @filesource
 *
 */

declare(strict_types=1); // strict mode
namespace Bluewater\Traits;

use Exception;

/**
 * PHP Singleton Control Class
 *
 * @author Grzegorz Synowiec - https://gist.github.com/Mikoj
 * @link https://gist.github.com/Mikoj
 *
 * @package     Bluewater_Core
 * @subpackage  Bluewater_Singleton
 *
 * @PHPUnit Not Defined
 *
 * @tutorial tutorial.pkg description
 * @example url://path/to/example.php description
 *
 */
trait Singleton
{
// ==========================================================
// Class Constants

// ==========================================================
// Class Properties

    /** @var array $instances */
    private static array $instances = [];

// ==========================================================
// Class Methods

    /**
     * Class constructor
     *
     * This is passed up to the parent class
     *
     * @private
     *
     * @param void
     * @return void
     *
     * @since 1.0
     *
     */
    abstract protected function __construct();

    /**
     * Singleton instance
     *
     * Determines if this class has been instantiated before, if so, sends back that Object,
     * if not, create a new one, stores it and sends that new one back.
     *
     * @param boolean $forceNewInstance Force a new instance regardless of previous state
     *
     * @return object Singleton instance of the class.
     */
    final public static function getInstance(bool $forceNewInstance = false): object
    {
        $called_class = static::class;
        $forceNewInstance = $forceNewInstance ?? false;
        if ($forceNewInstance || !isset(static::$instances[$called_class])) {
            try {
                static::$instances[$called_class] = new $called_class();
            } catch (Exception $e) {
                echo 'Caught exception: ' . $e->getMessage() . "\n";
            }
        }

        return static::$instances[$called_class];
    }

    /**
     * Prevent singletons from being cloned
     *
     * @private
     *
     * @param void
     * @return void
     *
     * @since 1.0
     *
     */
    private function __clone()
    {
        // Empty on purpose
    }

    /**
     * Prevent singletons from being serialized
     *
     * @private
     *
     * @param void
     * @return void
     *
     * @throws Exception
     *
     * @since 1.0
     *
     */
    public function __sleep(): array
    {
        throw new Exception('Cannot serialize singleton');
    }

    /**
     * Prevent singletons from being unserialized
     *
     * @access final
     * @private
     *
     * @param void
     * @return void
     *
     * @throws Exception
     *
     * @since 1.0
     *
     */
    public function __wakeup()
    {
        throw new Exception('Cannot unserialize singleton');
    }
}

// eof
