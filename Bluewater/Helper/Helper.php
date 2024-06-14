<?php

declare(strict_types=1);

namespace Bluewater\Helper;

use Bluewater\Traits\Singleton;
use Exception;

/**
 * Helper class.
 *
 * @author Walter Torres <walter@torres.ws>
 */
class Helper
{
// ==========================================================
// Class Traits
    use Singleton;

// ==========================================================
// Class Constants

// ==========================================================
// Class Properties

    /**
     * @var Helper|null Holds the single instance of this class.
     */
    private static ?Helper $instance = null;

// ==========================================================
// Class Methods

    /**
     * Private constructor to enforce the singleton pattern and load helper file.
     *
     */
    private function __construct()
    {
        // Intentionally left blank
    }

    /**
     * Load the helper file for a specific method.
     *
     * The helper file's name is determined based on the method name.
     * The file is expected to be in the same directory as this class file.
     *
     * @param string $method The method to load on class instantiation.
     * @throws Exception When the helper file does not exist.
     */
    private function loadHelperFile(string $method): void
    {
        $helperFile = __DIR__ . '/' . $method . '.php';

        if (false === file_exists($helperFile)) {
            throw new Exception("Helper file $helperFile does not exist.");
        }
        require_once $helperFile;
    }

    /**
     * Overloading method to handle calls to methods within helper classes.
     *
     * @param string $method The name of the method being called.
     * @param array $args The arguments to pass to the method.
     * @return mixed The result of the method call.
     * @throws Exception When the helper class for the method does not exist.
     */
    public function __call(string $method, array $args)
    {
        $helperClass = '\Bluewater\Helper\\' . ucfirst(strtolower($method));

        if (!class_exists($helperClass)) {
            throw new Exception("Method $method does not exist.");
        }

        $helperObject = new $helperClass;

        if (!method_exists($helperObject, 'helper')) {
            throw new Exception("The method helper does not exist in $method.");
        }

        return $helperObject->helper($args);
    }
}

#eof
