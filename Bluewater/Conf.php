<?php

declare(strict_types=1);

namespace Bluewater;

use Bluewater\File\FileHandler;
use Bluewater\File\DirectoryReader;
use Bluewater\Helper\Helper;
use Bluewater\Traits\Multiton;
use Exception;
use JetBrains\PhpStorm\NoReturn;

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
    private DirectoryReader $configPath;

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
        BLUEWATER . DS . 'Config',
        APP_ROOT . DS . 'Config'
    ];

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
        echo __METHOD__ . ': ' . __LINE__ . '<br>';

        self::$helper = Helper::getInstance();

        array_walk($this->configDirs, [$this, 'loadConfigFiles']);
    }

    /**
     * @param string $directory
     * @throws Exception
     */
    private function loadConfigFiles(string $directory): void
    {
        try {
            // Use DirectoryReader to scan the directory for .ini files
            $this->configPath = new DirectoryReader($directory, '/\.ini$/');
            $iniFiles = $this->configPath->getFiles();

            if (count($iniFiles) > 0) {
                // Call loadSerializedConfig if .ini files are found
                $this->loadSerializedConfig($iniFiles);
            } else {
                // Scan the directory for .ini.php files if no .ini files are found
                $directoryReader = new DirectoryReader($directory, '/\.ini\.php$/');
                $iniPhpFiles = $directoryReader->getFiles();

                if (count($iniPhpFiles) > 0) {
                    // Call loadRawConfig if .ini.php files are found
                    $this->loadRawConfig($iniPhpFiles);
                    $this->processAndSerializeConfig();

                    // Optionally, you can process the raw config as needed
                    // For demonstration, we'll just print the config
                } else {
                    throw new Exception("No configuration files found in directory: $directory");
                }
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() . '<br>';
        }
    }

    /**
     * Load serialized configuration from an array of FileHandler Objects.
     *
     * @param FileHandler[] $files array of FileHandler Objects.
     * @return void
     * @throws Exception
     */
    private function loadSerializedConfig(array $files): void
    {
        echo __METHOD__ . ': ' . __LINE__ . '<br>';
        print_r($files);
    }

    /**
     * Load unserialized configuration from an array of FileHandler Objects.
     *
     * @param FileHandler[] $files array of FileHandler Objects.
     * @return void
     * @throws Exception
     */
    private function loadRawConfig(array $files): void
    {
        $resultantIniArray = [];

        foreach ($files as $fileHandler) {
            if ($fileHandler->isFile()) {
                $basename = $fileHandler->getBasename();
                $parentname = $fileHandler->getPathInfo()->getFilename();

                $rawConfigs[$basename] = parse_ini_file($fileHandler->getRealPath(), true);

                foreach ($rawConfigs[$basename] as $key => $value) {
                    if (isset($resultantIniArray[$parentname][$key])) {
                        $resultantIniArray[$parentname][$key] = array_merge(
                            $resultantIniArray[$parentname][$key],
                            $value
                        );
                    } else {
                        $resultantIniArray[$parentname][$key] = $value;
                    }
                }
            }
        }

        $this->rawConfig = $resultantIniArray;
    }

    /**
     * Process and serialize configuration
     *
     * @throws Exception If there was an error writing to the file
     */
    private function processAndSerializeConfig(): void
    {
        echo __METHOD__ . ': ' . __LINE__ . '<br>';
        $this->processConfiguration();
        echo __METHOD__ . ': ' . __LINE__ . '<br>';
        print_r($this->rawConfig) . '<br>';
        exit;
        $serializedData = $this->serializeConfiguration();

        $filePath = $this->configPath->getDirectoryPath() . '/processed_config.ini';

        if (file_put_contents($filePath, $serializedData) === false) {
            throw new Exception('Unable to write to file: ' . $filePath);
        }
    }

    /**
     * Process the received configuration.
     *
     * Constants are processed in two passes to ensure the correct replacement of nested constants.
     * In the first pass, all settings are processed.
     * In the second pass, just the CONSTANTS are reprocessed.
     * This will ensure that every constant used in the definition of another constant is resolved.
     *
     * @return void
     *
     * @throws Exception If a constant is not found in predefined constants or CONSTANTS array
     */
    private function processConfiguration(): void
    {
        $processedConfig = [];

        echo __METHOD__ . ': ' . __LINE__ . '<br>';
        unset($this->rawConfig['New folder']);
        print_r($this->rawConfig) . '<br>';

        // First pass: process all sections and settings
        foreach ($this->rawConfig as $section => $settings) {
            echo __METHOD__ . ': ' . __LINE__ . '<br>';
            print_r($settings) . '<br>';

            foreach ($settings as $key => $value) {
                echo __LINE__ . ': ' . $key . '<br>';
                $processedConfig[$section][$key] = is_array($value)
                    ? $this->processArray($value)
                    : $this->processValue(
                        $value
                    );
            }
        }

        // Second pass: reprocess just the CONSTANTS section
        if (isset($processedConfig['CONSTANTS'])) {
            foreach ($processedConfig['CONSTANTS'] as $key => $value) {
                $processedConfig['CONSTANTS'][$key] = $this->processValue($value);
            }
        }

        // Update rawConfig with the result of two-pass processing
        $this->rawConfig = $processedConfig;
    }

    /**
     * Serialize configuration
     *
     * @param void
     *
     * @return string The serialized configuration
     */
    private function serializeConfiguration(): string
    {
        return serialize($this->rawConfig);
    }

    /**
     * Process array
     *
     * @param array $array The array to be processed
     *
     * @return array Processed array
     * @throws Exception
     */
    private function processArray(array &$array): array
    {
        echo __METHOD__ . ': ' . __LINE__ . '<br>';
        print_r($array) . '<br>';

        // Now process each item in the array
        foreach ($array as $key => &$value) {
            echo '<hr>' . __LINE__ . ': ' . $key . '<br>';
            echo __LINE__ . ': ' . $value . '<br>';

            // Replace value by reference
            $value = $this->processValue($value);

            echo __LINE__ . ': ' . $value . '<br>';
            print_r($array);
        }

        return $array;
    }


    /**
     * Process value
     *
     * @param mixed $value The value to be processed
     *
     * @return mixed Processed value
     *
     * @throws Exception
     */
    private function processValue($value): mixed
    {
        echo '<hr>';
        echo __METHOD__ . ': ' . __LINE__ . '<br>';
        echo __LINE__ . ': ' . $value . '<br>';
        print_r($this->rawConfig) . '<br>';

        if (is_string($value) && strpos($value, '{') !== false) {
            preg_match_all('/\{(.+?)\}/', $value, $matches);
            foreach ($matches[1] as $match) {
                echo $match . ': ' . __LINE__ . '<br>';

                if (defined($match)) {
                    $value = str_replace("{{$match}}", constant($match), $value);
                    echo __LINE__ . ': ' . $value . '<br>';
                } elseif (isset($this->rawConfig['Config']['constants'][$match])) {
                    $value = str_replace("{{$match}}", $this->rawConfig['Config']['constants'][$match], $value);
                    echo __LINE__ . ': ' . $value . '<br>';
                } else {
                    throw new Exception("Constant '$match' not found in predefined constants or CONSTANTS array");
                }
            }
        }

        echo __LINE__ . ': ' . $value . '<hr>';
        return $value;
    }

    /**
     * Replace constants in value
     *
     * @param string $value The value in which constants are replaced
     * @param array $matches The matches that contains constant keys
     *
     * @return string The value with replaced constants
     */
    /**
     * Replace constants in value
     *
     * @param string $value The value in which constants are replaced
     * @param array $matches The matches that contains constant keys
     *
     * @return string The value with replaced constants
     *
     * @throws Exception If a constant is not found
     */
    private function replaceConstantsInValue(string $value, array $matches): string
    {
        foreach ($matches as $match) {
            if (defined($match)) {
                $value = str_replace("{{$match}}", constant($match), $value);
            } elseif (isset($this->rawConfig['CONSTANTS'][$match])) {
                $value = str_replace("{{$match}}", $this->rawConfig['CONSTANTS'][$match], $value);
            } else {
                throw new Exception("Constant '{$match}' not found in predefined constants or CONSTANTS array");
            }
        }

        return $value;
    }
}

// eof
