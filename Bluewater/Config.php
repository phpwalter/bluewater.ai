<?php

declare(strict_types=1);

namespace Bluewater;

use Bluewater\Helper\Helper;
use Bluewater\Traits\Singleton;
use DirectoryIterator;
use Bluewater\Exception\BluewaterException;


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
 * This class allows you to convert the specified ini file into a multidimensional
 * array. In this case, the structure generated will be:
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
    use Singleton;

    private const CONFIG_PATH = '/Config';
    private const INI_EXTENSION = '.ini.php';
    private static string $saveFile = BLUEWATER . '/Bluewater.ini';
    private static ?Helper $helper = null;
    private static ?array $conf = null;

    /**
     * Config constructor
     * @param bool $process_sections If sections in the INI file should be processed
     * @throws BluewaterException
     * @throws Exception
     */
    public function __constructX(bool $process_sections = true)
    {
        self::$helper = Helper::getInstance();

        try {
            $this->loadAllConf($process_sections);
        } catch (BluewaterException $e) {
            throw $e;
        }
    }

    /**
     * Loads all configuration files
     * @throws BluewaterException
     */
    private function loadAllConf(): mixed
    {``
        $baseName = 'Bluewater.ini';
        $alternativeName = 'Bluewater.ini.php';

        if (file_exists($baseName)) {
            $data = file_get_contents($baseName);
            if ($unserializedData = @unserialize($data)) {
                return $unserializedData;
            }
        }

        if (file_exists($alternativeName)) {
            return $this->parseIniFile($alternativeName);
        }

        throw new InvalidArgumentException("No valid configuration file found.");
    }

    /**
     * Returns an array of config files names from the given directory
     * @param string $directory The directory to search for config files
     * @return array array of config files names
     */
    private function getConfigFiles(string $directory): array
    {
        $configFiles = [];

        if (is_dir($directory)) {
            foreach (new DirectoryIterator($directory) as $fileInfo) {
                $fileName = $fileInfo->getFilename();

                if ($fileInfo->isFile() && str_ends_with($fileName, self::INI_EXTENSION)) {
                    $configFiles[] = $fileInfo->getPath();
                }
            }
        }

        return $configFiles;
    }

    /**
     * Load and parse Serialized configuration data from a file
     * @return bool|array The parsed configuration data if successful, or null on failure
     */
    private static function loadSerializedConf(): bool|array
    {
        $configData = null;

        serialize();
        echo self::$saveFile . '<br>';
        echo __LINE__ . '<br>';
        echo __FUNCTION__ . '<p>';

        try {
            $saveData = file_get_contents(self::$saveFile);

            $saveData = unserialize($saveData, ['allowed_classes' => false]);
            echo '<pre>';
            print_r($saveData);
            exit;
            if ($saveData !== false) {
                $configData = self::$helper->SafeUnserialize($saveData);
            }
        } catch (Exception $e) {
            // Potentially log the exception message somewhere
        }

        return is_array($configData) ? $configData : false;
    }

    /**
     * Sets the default time zone from the config
     */
    private function setDefaultTimezone(): void
    {
        $timeZone = self::config('general', 'tz');

        if (!empty($timeZone)) {
            date_default_timezone_set($timeZone);
        }
    }
}
#eof
