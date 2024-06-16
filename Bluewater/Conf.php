<?php

declare(strict_types=1);

namespace Bluewater;

use Bluewater\Traits\Singleton;

class Conf
{

// ==========================================================
// Class Traits
    use Singleton;

// ==========================================================
// Class Constants


// ==========================================================
// Class Properties

    private array $dirs = [
        BLUEWATER . '/Config'
        ,
        SITE_ROOT . '/App/Conf'
    ];

    /**
     * Directory to scan
     *
     * @var string
     */
    private string $directoryToScan;

    /**
     * The array which will hold constants and configs extracted from '.ini.php' files.
     *
     * @var array
     */
    private array $constantsAndConfigs = [];


// ==========================================================
// Class Methods


    public function __construct()
    {
        $this->checkDirectoryForIniFiles($this->dirs[0]);
    }

    /**
     * Recursive function to check directories for '.ini.php' files, generate data for '.ini' files
     * and create '.ini' files if none exist in the directory.
     *
     * @param string $directory
     * @throws Exception
     */
    private function checkDirectoryForIniFiles(string $directory): void
    {
        // Open directory for reading
        if (!$handle = opendir($directory)) {
            throw new Exception("Could not open directory: $directory");
        }

        // Read all entries in the directory
        while (($entry = readdir($handle)) !== false) {
            if ($entry !== "." && $entry !== "..") {
                $entryPath = $directory . '/' . $entry;

                if (is_dir($entryPath)) {
                    // Recursive call for subdirectories
                    $this->checkDirectoryForIniFiles($entryPath);
                } elseif ($entry === 'ini.php') {
                    // Extract all arrays from the 'ini.php' file
                    $this->extractData($entryPath);
                }
            }
        }

        // At this point we've done a full scan of $directory
        $directoryArr = explode('/', $directory);

        // We'll need to filter out empty string caused by the last '/'
        $filteredArr = array_filter($directoryArr);

        // Get the directory's name (i.e. the last value in the array)
        $directoryName = end($filteredArr);

        if (!file_exists($directory . '/' . $directoryName . '.ini')) {
            $this->createIniFile($directory, $directoryName . '.ini', $this->constantsAndConfigs);
        }

        closedir($handle);
    }

    /**
     * Extracts the arrays from '.ini.php' file into the $constantsAndConfigs array property.
     *
     * @param string $iniFilePath
     * @throws Exception
     */
    private function extractData(string $iniFilePath): void
    {
        $arrayFromIniPHPFile = include($iniFilePath);

        if (!is_array($arrayFromIniPHPFile)) {
            throw new Exception("Return value of included 'ini.php' file is not an array: $iniFilePath");
        }

        $this->constantsAndConfigs = array_merge($this->constantsAndConfigs, $arrayFromIniPHPFile);
    }

    /**
     * Uses parsed data from '.ini.php' files to create a '.ini' file in.directory, excluding CONSTANTS from configs array
     *
     * @param string $directory
     * @param string $filename
     * @param array $data
     * @throws Exception
     */
    private function createIniFile(string $directory, string $filename, array $data): void
    {
        $iniFileContent = $this->generateIniFileContent($data);

        if (file_put_contents($directory . '/' . $filename, $iniFileContent) === false) {
            throw new Exception("Failed to create .ini file: $directory/$filename");
        }
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

        // REmove CONSTANTS
        $filteredConfigs = array_filter($data, function ($k) {
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
