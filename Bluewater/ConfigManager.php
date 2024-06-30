<?php

declare(strict_types=1);

namespace Bluewater;

use Bluewater\File\DirectoryReader;
use Bluewater\File\FileHandler;
use RuntimeException;

/**
 * Class ConfigManager
 *
 * Manages configuration by reading from .ini and .ini.php files,
 * defines constants, and provides access to configuration values.
 */
class ConfigManager
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
     * @var array Directories to be processed
     */
    private array $directories;

    /**
     * @var array Merged configuration data
     */
    private array $configData = [];

    /**
     * @var array Constants to be defined at the system level
     */
    private array $constants = [];

    /**
     * ConfigManager constructor.
     *
     * @param array $directories Directories to be processed
     */
    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    /**
     * Processes all directories to discover and parse configuration files.
     *
     * @throws RuntimeException if there are errors in file processing or unserializing
     */
    public function processDirectories(): void
    {
        foreach ($this->directories as $directory) {
            try {
                $directoryReader = new DirectoryReader($directory);

                $iniFilePath = $directoryReader->getIniFile();
                if ($iniFilePath !== null) {
                    $this->processIniFile($iniFilePath);
                } else {
                    $this->processIniPhpFiles($directoryReader->getIniPhpFiles());
                }
            } catch (Exception $e) {
                throw new RuntimeException("Error processing directory '$directory': " . $e->getMessage());
            }
        }
        $this->defineConstants();
    }

    /**
     * Processes a .ini file by unserializing its content and merging it into the config data.
     *
     * @param string $filePath Path to the .ini file
     *
     * @throws RuntimeException if file content cannot be unserialized
     */
    private function processIniFile(string $filePath): void
    {
        try {
            $content = FileHandler::getFileContent($filePath);
            $data = FileHandler::unserializeContent($content);
            $this->configData = array_merge_recursive($this->configData, $data);
        } catch (Exception $e) {
            throw new RuntimeException("Error processing INI file '$filePath': " . $e->getMessage());
        }
    }

    /**
     * Processes all .ini.php files in the given array of file paths by parsing and merging their content.
     *
     * @param array $files Array of .ini.php file paths
     *
     * @throws RuntimeException if an INI file cannot be parsed
     */
    private function processIniPhpFiles(array $files): void
    {
        foreach ($files as $filePath) {
            try {
                $data = FileHandler::parseIniFile($filePath);
                $this->configData = array_merge_recursive($this->configData, $data);
            } catch (Exception $e) {
                throw new RuntimeException("Error processing INI PHP file '$filePath': " . $e->getMessage());
            }
        }
    }

    /**
     * Defines system-level constants from the CONSTANTS sub-array in the config data.
     *
     * @throws RuntimeException if a constant reference is undefined
     */
    private function defineConstants(): void
    {
        if (isset($this->configData['CONSTANTS'])) {
            $this->constants = $this->configData['CONSTANTS'];
            foreach ($this->constants as $key => $value) {
                try {
                    $resolvedValue = $this->resolveReferences((string)$value);
                    if (!defined($key)) {
                        define($key, $resolvedValue);
                    }
                } catch (Exception $e) {
                    throw new RuntimeException("Error defining constant '$key': " . $e->getMessage());
                }
            }
            unset($this->configData['CONSTANTS']);
        }
    }

    /**
     * Resolves references in a value by replacing placeholders with actual constant values.
     *
     * @param string $value Value containing references to be resolved
     *
     * @return string Resolved value
     *
     * @throws RuntimeException if a referenced constant is undefined
     */
    private function resolveReferences(string $value): string
    {
        return preg_replace_callback('/\{([A-Z_]+)\}/', function ($matches) {
            $constantName = $matches[1];
            if (isset($this->constants[$constantName])) {
                return $this->resolveReferences((string)$this->constants[$constantName]);
            } elseif (defined($constantName)) {
                return (string)constant($constantName);
            }
            throw new RuntimeException("Undefined constant referenced: $constantName");
        }, $value);
    }

    /**
     * Retrieves a configuration value by section and key.
     *
     * @param string $section Section name
     * @param string $key Key name
     *
     * @return mixed|null Configuration value or null if not found
     */
    public function config(string $section, string $key): mixed
    {
        return $this->configData[$section][$key] ?? null;
    }
}


#eof
