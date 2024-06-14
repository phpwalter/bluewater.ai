<?php

declare(strict_types=1);

/**
 * Singleton Trait
 *
 * Singleton instance trait to ensure only single instance of any class using this trait.
 * Exception handling is done in more extensive way.
 *
 * PHP Version >= 8.0
 *
 * @category Singleton
 * @package  Traits
 * @author   Walter Torres <walter@torres.ws>
 * @link     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 */

namespace Bluewater\Traits;

use Exception;

/**
 * Managing Singleton Instances
 *
 * @category Singleton
 * @package  Traits
 * @author   Walter Torres <walter@torres.ws>
 * @link     http://web.bluewatermvc.org
 *
 */
trait Singleton
{

// ==========================================================
// Class Traits

// ==========================================================
// Class Constants

// ==========================================================
// Class Properties

    /**
     * Array to hold instances
     *
     * @var array
     */
    private static array $instances = [];


// ==========================================================
// Class Methods

    /**
     * Singleton Implementation
     *
     * getInstance method returns a singleton instance
     * of the class. If $forceNewInstance is set to true,
     * it will force to create a new instance despite a
     * instance has already been created
     *
     * @param bool $forceNewInstance flag to force creation of new instance
     *
     * @return object Singleton Instance of the class
     * @throws Exception
     */
    final public static function getInstance(bool $forceNewInstance = false): object
    {
        $called_class = static::class;

        if ($forceNewInstance || !isset(static::$instances[$called_class])) {
            try {
                static::$instances[$called_class] = new $called_class();
            } catch (Exception $exception) {
                throw new Exception(
                    "An exception occurred while creating an singleton instance: " . $exception->getMessage()
                );
            }
        }

        return static::$instances[$called_class];
    }

    /**
     * Singleton Classes should not be clone-able
     */
    private function __clone()
    {
        // Empty on purpose
    }

    /**
     * Singleton Classes should not be serialized
     *
     * @return array
     * @throws Exception
     */
    public function __sleep(): array
    {
        throw new Exception('Cannot serialize singleton');
    }

    /**
     * Singleton Classes should not be un-serialized
     *
     * @return void
     * @throws Exception
     */
    public function __wakeup(): void
    {
        throw new Exception('Cannot unserialize singleton');
    }

    /**
     * Declare an abstract constructor to ensure Singleton class will not be instantiated directly
     *
     * @abstract
     */
    abstract protected function __construct();

}

# eof
