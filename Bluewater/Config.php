<?php

declare(strict_types=1);

namespace Bluewater;

use Bluewater\Helper\Helper;
use Bluewater\Traits\Singleton;

/**
 * Allows for multidimensional ini files.
 *
 * The native parse_ini_file() function will convert the following ini file:...
 *
 * [production]
 * localhost.database.host = 192.168.0.500
 * localhost.database.user = my_id
 * localhost.database.password = myPassword
 *
 * [development:production]
 * localhost.database.host = localhost
 *
 * This class allows you to convert the specified ini file into a multi-dimensional
 * array. In this case the structure generated will be:
 *
 * array
 *   'localhost' =>
 *     array
 *       'database' =>
 *         array
 *           'host' => 'localhost'
 *           'user' => 'root'
 *           'password' => 'myPassword'
 *
 * As you can also see you can have sections that extend other sections (use ":" for that).
 * The extendable section must be defined BEFORE the extending section or otherwise
 * you will get an exception.
 *
 * BOOLEANS are also a special case.
 * debug_enabled = 'true' will be converted into a true BOOLEAN, where as
 * debug_enabled = true will be converted to an INT of '1'
 *
 * This class also allows for string substitution.
 * location = {APP_ROOT}/locale will be converted to the value defined in APP_ROOT
 */


/**
 * Configuration Class to process Bluewater 8 MVC Core config file
 * and an application level config file.
 *
 * Configuration files are standard INI format file with additional features of:
 *  - string substitution
 *  - true boolean conversion
 *  - multidimensional arrays from DOT delimited strings
 *
 * @package     Bluewater8_Core
 * @subpackage  Support
 * @link        http://web.bluewatermvc.org
 *
 * @howto {@link http://guides.bluewatermvc.org/doku.php/dev/classes/general/config#configuration_class}
 * @example url://path/to/example.php description
 *
 * @PHPUnit Not Defined
 *
 * @tutorial tutorial.pkg description
 * @example url://path/to/example.php description
 *
 * @todo Create PHPUnit test, tutorials, and example files for this class
 * @todo look into converting this into a DBA access type class via SPL
 * @todo add memcach and DB config storage options
 *
 */
class Config
{

// ==========================================================
// Class Traits
    use Singleton;

// ==========================================================
// Class Constants
    private const string CONFIG_PATH = '/Config';
    private const string INI_EXTENSION = '.ini.php';

// ==========================================================
// Class Properties

    /**
     * Class instance
     *
     * @var string
     * @access private
     * @static
     *
     * @since 1.0
     */
    private static string $saveFile = BLUEWATER . '/Bluewater.ini';

    /**
     * Helper Object Container
     *
     * @var Helper $helper
     * @access private
     * @static
     *
     * @since 1.0
     */
    private static Helper $helper;

// ==========================================================
// Class Methods


    final protected function __construct(bool $process_sections = true)
    {
        // Load Helper Support Class
        self::$helper = Helper::getInstance();

        if (!self::loadConf()) {
            // Path to main Config file to load raw data
            try {
                self::load(BLUEWATER . '/Bluewater.ini.php', $process_sections);
            } catch (BluewaterException $e) {
                throw new BluewaterException ((string)$e);
            }

            $this->loadAllAppConfigFiles($process_sections);

            // Load all INI files in APP Config directory
//            if( is_dir(\APP_ROOT . '/Config') ) {
//                foreach (new DirectoryIterator(\APP_ROOT . '/Config') as $ini_file) {
//                    if ($ini_file->isFile()) {
//                        if (str_ends_with($ini_file->getFilename(), 'ini.php')) {
//                            self::load(\APP_ROOT . '/Config/' . $ini_file->getFilename(), $process_sections);
//                        }
//                    }
//                }
//
//                // Parse config data
//                self::parse();
//
//                // Transfer temp data into config array
//                self::$conf = self::$result;
//                self::$result = null;
//
//                // Process i18n settings
//                /**
//                 * @TODO get bindtextdomain/gnu_gettext.dll to work
//                 */
////        self::setLocale();
//
//
//                /**
//                 * @TODO need to thrown an exception if TZ is not defined
//                 */
//                if (self::config('general', 'tz')) {
//                    // Set default TZ
//                    date_default_timezone_set((string)self::config('general', 'tz'));
//                }
//
//                self::saveConf();
//            }
//            else {
//                throw new BluewaterException ('"' . \APP_ROOT . '/Config" not found');
//            }
        }
    }

    /**
     * Attempt to load SERIALIZED conf data
     *
     * @param void
     * @return bool
     *
     * @uses property $conf
     *
     * @static
     *
     * @author Walter Torres <walter@torres.ws>
     *
     * @uses property $saveFile
     * @since 1.0
     *
     * @PHPUnit Not Defined
     */
    private static function loadConf(): bool
    {
        $success = false;

        $configData = self::loadAndParseConfig();

        if ($configData !== null) {
            self::$conf = $configData['conf'];

            // Define constants from the configuration data
            foreach ($configData['constants'] as $constant => $value) {
                if (!defined(strtoupper($constant))) {
                    define(strtoupper($constant), $value);
                }
            }
            $success = true;
        }

        return $success;
    }

    /**
     * Load and parse configuration data from a file
     *
     * @static
     *
     * @return ?array The parsed configuration data if successful, or null on failure
     *
     * @since 1.0
     */
    private static function loadAndParseConfig(): ?array
    {
        try {
            $configData = self::$helper->safe_unserialize(self::$saveFile);
            //    $configData = safe_unserialize(self::$saveFile);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }


        if ($configData === false || !is_array($configData)) {
            // Properly handle and potentially log the error
            // The unserialize() function returns FALSE on failure
            // or if $fileContent is not a string. It's also possible the unserialized data is not an array
            return null;
        }

        return $configData;
    }
}

#eof
