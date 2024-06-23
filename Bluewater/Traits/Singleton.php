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
 * @package  Bluewater\Traits
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
     * @var self|null The single instance of the class
     */
    private static self|null $instance = null;


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
     * @return self The *Singleton* instance
     * @throws Exception
     */
    final public static function getInstance(): self
    {
        try {
            return self::$instance ?? self::$instance = new static();
        } catch (Exception $e) {
            throw new Exception('Could not create instance of singleton class: ' . $e->getMessage());
        }
    }

    /**
     * Singleton constructor. Is declared private to prevent direct object creation.
     */
    final private function __construct()
    {
        $this->init();
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

# eof
