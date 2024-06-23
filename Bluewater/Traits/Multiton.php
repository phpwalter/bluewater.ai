<?php

declare(strict_types=1);

namespace Bluewater\Traits;

use Exception;

/**
 * Managing Multiton Instances
 *
 * This trait applies the Multiton design pattern. A class that uses this trait
 * can have multiple named instances.
 *
 * @category DesignPattern
 * @package  Bluewater\Traits
 * @author   Walter Torres <walter@torres.ws>
 * @link     http://web.bluewatermvc.org
 *
 */
trait Multiton
{

// ==========================================================
// Class Traits


// ==========================================================
// Class Constants


// ==========================================================
// Class Properties

    /**
     * @var array The array of instances
     */
    private static array $instances = [];

// ==========================================================
// Class Methods

    /**
     * Multiton Instance
     *
     * This method returns a Multiton instance of a class based on the instance key.
     *
     * @param string $instanceKey Key for the instance.
     *
     * @return self The Multiton instance of the class
     * @throws Exception if there's an error creating the instance
     */
    final public static function getInstance(string $instanceKey = null): self
    {
        if (!isset(self::$instances[$instanceKey])) {
            try {
                self::$instances[$instanceKey] = new static();
            } catch (Exception $e) {
                throw new Exception(
                    'Failed to create an instance of ' . __CLASS__ . ' Error: ' . $e->getMessage(),
                    $e->getCode(),
                    $e
                );
            }
        }

        return self::$instances[$instanceKey];
    }

    /**
     * Multiton constructor
     * Declared private to prevent from creating instances directly
     * @throws Exception
     */
    final private function __construct()
    {
        if (method_exists($this, 'init')) {
            try {
                $this->init();
            } catch (Exception $e) {
                throw new Exception(
                    'Failed to initialize an instance of ' . __CLASS__ . ' Error: ' . $e->getMessage(),
                    $e->getCode(),
                    $e
                );
            }
        }
    }

    /**
     * Initialization method.
     * If initialization is needed, it can be overwritten by singleton classes.
     */
    protected function init(): void
    {
    }

    /**
     * Method declared private to prevent unserialization of an instance of the *Singleton* class.
     */
    final public function __wakeup()
    {
        throw new Exception('Cannot unserialize singleton');
    }

    /**
     * Method is declared private to prevent cloning of an instance of the *Singleton* class.
     */
    private function __clone()
    {
    }


    /**
     * Singleton Classes should not be serialized
     *
     * @return array
     * @throws Exception
     */
    final public function __sleep(): array
    {
        throw new Exception('Cannot serialize singleton');
    }
}

// eof
