<?php

declare(strict_types=1);

namespace Bluewater\File;

use Bluewater\Exception\BluewaterDirException;
use FilesystemIterator;
use InvalidArgumentException;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Exception;
use RuntimeException;
use SplFileInfo;
use UnexpectedValueException;

/**
 * Class DirectoryReader
 * Reads the directory tree.
 * @package YourNamespace
 */
class DirectoryReader
{

// ==========================================================
// Class Traits


// ==========================================================
// Class Constants


// ==========================================================
// Class Properties

    private string $directory;

    /**
     * DirectoryReader constructor.
     *
     * @param string $directory The directory to read.
     * @throws Exception If the directory does not exist or is not readable.
     */

// ==========================================================
// Class Methods

    /**
     * DirectoryReader constructor.
     *
     * @param string $directory The directory to read.
     * @throws Exception If the directory does not exist or is not readable.
     */
    public function __construct(string $directory)
    {
        if (!is_dir($directory) || !is_readable($directory)) {
            throw new BluewaterDirException('Error reading directory: ');
        }

        $this->directory = realpath($directory);
    }

    /**
     * Get all files in the directory and its optionally subdirectories.
     *
     * @param bool $includeSubdirectories Optional parameter to scan subdirectories
     * @return array An array of file paths.
     * @throws Exception
     */
    public function getFiles(array $suffixes = [], bool $includeSubdirectories = false): array
    {
        return $this->getFilesFromDirectory($suffixes, $includeSubdirectories);
    }

    /**
     * Retrieves files from a specified directory based on provided conditions.
     *
     * @param array $suffixes Array of suffixes to filter files (default: empty array, includes all files).
     * @param bool $includeSubdirectories Flag to determine whether to include subdirectories (default: false).
     *
     * @return array Returns an array of SplFileInfo Objects.
     *
     * @throws UnexpectedValueException if the path cannot be found
     * @throws InvalidArgumentException if the mode is invalid
     *
     * @see RecursiveDirectoryIterator
     * @see RecursiveIteratorIterator
     */
    private function getFilesFromDirectory(array $suffixes = [], bool $includeSubdirectories = false): array
    {
        $iterationType = $includeSubdirectories ? RecursiveIteratorIterator::SELF_FIRST : RecursiveIteratorIterator::LEAVES_ONLY;
        $directoryIterator = new RecursiveDirectoryIterator($this->directory, FilesystemIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directoryIterator, $iterationType);

        $files = [];
        foreach ($iterator as $file) {
            if ($this->isValidFile($file, empty($suffixes), array_flip($suffixes))) {
                $files[] = $file;
            }
        }
        return $files;
    }

    /**
     * Check if a file is valid
     *
     * @param SplFileInfo $file Instance of file to be validated
     * @param bool $returnAllFiles Flag to return all files if set to true
     * @param array $suffixes Array of file extensions
     * @return bool                                Returns true if file is valid
     * @throws UnexpectedValueException            Throw exception if file fails to process
     */
    public function isValidFile(SplFileInfo $file, bool $returnAllFiles, array $suffixes): bool
    {
        $valid = false;
        try {
            // Validating if the file is indeed a file, not a directory
            if (($file->isFile() && $returnAllFiles) || $this->fileHasValidSuffix($file, $suffixes)) {
                $valid = true;
            }
        } catch (UnexpectedValueException $ex) {
            throw new UnexpectedValueException(
                sprintf('Failed to process file: %s. Exception: %s', $file->getBasename(), $ex->getMessage()), 0, $ex
            );
        } catch (Exception $e) {
            throw new RuntimeException(
                sprintf(
                    'Unexpected error occurred while processing file: %s. Exception: %s',
                    $file->getBasename(),
                    $e->getMessage()
                ), 0, $e
            );
        }

        return $valid;
    }

    /**
     * Checks if file has a valid suffix
     *
     * @param SplFileInfo $file Instance of file to be validated
     * @param array $suffixes Array of valid file extensions
     * @return bool           Returns true if file has valid suffix
     */
    public function fileHasValidSuffix(SplFileInfo $file, array $suffixes): bool
    {
        $valid = false;

        $filename = $file->getBasename();

        foreach ($suffixes as $suffix => $value) {
            if (str_ends_with($filename, $suffix)) {
                $valid = true;
            }
        }

        return $valid;
    }
}


// eof
