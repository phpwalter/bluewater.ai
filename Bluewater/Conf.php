<?php

declare(strict_types=1);

namespace Bluewater;

use Bluewater\File\DirectoryReader;
use Bluewater\Exception\BluewaterDirException;
use Bluewater\Exception\BluewaterFileException;
use Bluewater\Helper\Helper;
use Bluewater\Traits\Multiton;
use Exception;

use PhpStreams\Stream;

class Conf
{

// ==========================================================
// Class Traits
    use Multiton;

// ==========================================================
// Class Constants


// ==========================================================
// Class Properties

    /**
     * The path to the configuration files.
     */
    private const CONFIG_PATH = '/Config';


    /**
     * An instance of the Helper class, used for helper functions.
     *
     * @var Helper|null
     */
    private static ?Helper $helper = null;

    /**
     * The loaded configuration data.
     *
     * @var array|null
     */
    private static ?array $conf = null;

    private array $configDirs = [
        BLUEWATER . DS . 'Config'
        ,
        APP_ROOT . DS . 'Config'
    ];

    /**
     * Directory to scan
     *
     * @var DirectoryReader
     */
    private DirectoryReader $directoryToScan;

    /**
     * The array which will hold constants and configs extracted from '.ini.php' files
     * as separate keys.
     *
     * @var array
     */
    private array $rawConfig = [];

    /**
     * The array which will hold merged keys of constants and configs.
     *
     * @var array
     */
    private array $constantsAndConfigs = [];


// ==========================================================
// Class Methods

    /**
     * @throws Exception
     */
    public function init(): void
    {
        echo __LINE__ . '<br>';
        self::$helper = Helper::getInstance();

        array_walk($this->configDirs, [$this, 'loadIniFiles']);
    }


    /*
     * There are 2 directories to look into:
     *  - Bluewater\Config
     *  - App\Config
     *
     * Each directory will contain one or more '*.ini.php' files.
     * These are the raw configuration data.
     *
     * Each directory may, or may not, contains a single "*.ini'.
     * This is the serialized config data for faster processing on
     * subsequent application loads
     *
     * 1) Check Bluewater\Config for the existence of a single "*.ini" file
     *  a) if found, load and unserialize
     *  b) if not found, load all "*.ini" files and serialize the data
     *     and place in a new "bluewater.ini"
     *
     * 2) Repeat #1 for "App\Config" directory.
     *
     *
     */

    /**
     * @param string $directory
     * @throws Exception
     */
    private function loadIniFiles(string $directory): void
    {
        // This is used in other places. No need to pass it around
        $this->directoryToScan = new DirectoryReader($directory);

        echo '<pre>';
        print_r($this->directoryToScan) . "<br>";

        try {
            $files = $this->directoryToScan->getFiles(['ini']);

            // We have a SERIALIZED INI file, load it
            if ($files) {
                $this->loadSerializedConfig($files[0]);
            } // Otherwise, load all 'ini.php' files
            else {
                $files = $this->directoryToScan->getFiles(['ini.php']);

                if ($files) {
                    $this->loadRawConfig($files);
                    $this->generateIniFileContent();
                }
//                else {
//                    throw new BluewaterDirException("Directory, $directory, does not contain RAW INI files");
//                }
            }
        } catch (Exception $e) {
            //           throw new BluewaterDirException($directory, 11, $e);
        }
    }

    /**
     * Load serialized configuration from an array of SplFileInfo Objects.
     *
     * @param array $files array of SplFileInfo Objects.
     * @return void
     * @throws Exception
     */
    private function loadSerializedConfig(array $files): void
    {
        echo __METHOD__ . ': ' . __LINE__ . '<br>';
        print_r($files);
    }

    /**
     * Load unserialized configuration from an array of SplFileInfo Objects.
     *
     * @param array $splFileInfoObjects array of SplFileInfo Objects.
     * @return void
     * @throws Exception
     */
    private function loadRawConfig(array $splFileInfoObjects): void
    {
        echo __METHOD__ . ': ' . __LINE__ . '<br>';

        $resultantIniArray[$this->directoryToScan] = [];

        foreach ($splFileInfoObjects as $file) {
            if ($file->isFile()) {
                $basename = $file->getBasename();
                $parentname = $file->getPathInfo()->getFilename();

                $rawConfigs[$basename] = parse_ini_file($file->getRealPath(), true);

                foreach ($rawConfigs[$basename] as $key => $value) {
                    if (isset($resultantIniArray[$this->directoryToScan][$key])) {
                        $resultantIniArray[$this->directoryToScan][$key] = array_merge(
                            $resultantIniArray[$this->directoryToScan][$key],
                            $value
                        );
                    } else {
                        $resultantIniArray[$this->directoryToScan][$key] = $value;
                    }
                }
            }
        }


        print_r($resultantIniArray) . '<br>';
        exit;
    }

    /**
     * Recursive function to check directories for '.ini.php' files, generate data for '.ini' files
     * and create '.ini' files if none exist in the directory.
     *
     * @param string $directory
     * @throws Exception
     */
    private function loadIniFilesXX(string $directory): void
    {
        echo $directory . "<br>";

        // Open directory for reading

        /** @TODO Need better exception handling */
        try {
            $dirHandle = self::$helper->CheckDir($directory);
        } catch (BluewaterDirException $e) {
            echo 'oops: ' . $directory;
            exit;
        }

        // Read all entries in the directory
        while (($entry = readdir($dirHandle)) !== false) {
            if ($entry !== "." && $entry !== "..") {
                $filePath = $directory . DS . $entry;

                if (is_dir($filePath)) {
                    // Recursive call for subdirectories
                    $this->loadIniFiles($filePath);
                } elseif (str_ends_with($filePath, 'ini.php')) {
                    $this->extractData($filePath);
                }
            }
        }


//
//        // At this point we've done a full scan of $directory
//        $directoryArr = explode('/', $directory);
//
//        // We'll need to filter out empty string caused by the last '/'
//        $filteredArr = array_filter($directoryArr);
//
//        // Get the directory's name (ie: the last value in the array)
//        $directoryName = end($filteredArr);
//
//        if (!file_exists($directory . '/' . $directoryName . '.ini')) {
//            $this->createIniFile($directory, $directoryName . '.ini', $this->constantsAndConfigs);
//        }

        closedir($handle);
    }

    /**
     * Generates the content for the new '.ini' file as a string.
     *
     * @param array $data
     * @return string
     */
    private function generateIniFileContent(array $data): string
    {
        $contentConfigs = "";

        // Remove CONSTANTS
        $filteredConfigs = array_filter($data, static function ($k) {
            return $k !== 'constants';
        }, ARRAY_FILTER_USE_KEY);

        // Prepare content for Configs
        foreach ($filteredConfigs as $key => $value) {
            $contentConfigs .= "$key = \"$value\"\n";
        }

        $iniFileContent = "[config]\n" . $contentConfigs;

        // Prepare content for CONSTANTS
        $contentConstants = '';
        foreach ($data['constants'] as $key => $value) {
            $contentConstants .= "$key = \"$value\"\n";
        }

        $iniFileContent .= "\n[constants]\n" . $contentConstants;

        return $iniFileContent;
    }
}


// eof
