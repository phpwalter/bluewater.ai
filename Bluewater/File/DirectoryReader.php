<?php

declare(strict_types=1);

namespace Bluewater\File;

use DirectoryIterator;
use Exception;
use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use RuntimeException;
use SplObjectStorage;


/**
 * DirectoryReader class for managing directory traversal and file filtering.
 *
 * This class provides functionality to traverse a directory, filter files based on a pattern,
 * sort files, filter files by size and date, and interact with the FileHandler class for individual file operations.
 * Additionally, it wraps various methods from the SPL for comprehensive directory management.
 *
 * @package FileManager
 */
class DirectoryReader
{
    private string $directory;
    private string $pattern;
    private SplObjectStorage $files;

    /**
     * DirectoryReader constructor.
     *
     * @param string $directory The directory to manage.
     * @param string $pattern The pattern to filter files.
     * @throws InvalidArgumentException If the directory does not exist or is not readable.
     */
    public function __construct(string $directory, string $pattern = '/.*/')
    {
        if (!is_dir($directory) || !is_readable($directory)) {
            throw new InvalidArgumentException("Directory not found or not readable: $directory");
        }

        $this->directory = $directory;
        $this->pattern = $pattern;
        $this->files = new SplObjectStorage();

        try {
            $this->loadFiles();
        } catch (Exception $e) {
            throw new RuntimeException("Failed to load files: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Get the directory path.
     *
     * @return string The directory path.
     */
    public function getDirectoryPath(): string
    {
        return $this->directory;
    }

    /**
     * Load files from the directory based on the pattern and store them in SplObjectStorage.
     *
     * @throws RuntimeException If an error occurs while loading files.
     */
    private function loadFiles(): void
    {
        try {
            $directoryIterator = new RecursiveDirectoryIterator($this->directory);
            $iterator = new RecursiveIteratorIterator($directoryIterator);
            $regexIterator = new RegexIterator($iterator, $this->pattern);

            foreach ($regexIterator as $file) {
                $filePath = $file->getPathname();
                if (file_exists($filePath)) {
                    $fileHandler = new FileHandler($filePath);
                    $this->files->attach($fileHandler);
                }
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error loading files: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Get the list of files as FileHandler objects.
     *
     * @return FileHandler[] The array of FileHandler objects.
     * @throws RuntimeException If an error occurs while creating FileHandler objects.
     */
    public function getFiles(): array
    {
        $fileHandlers = [];

        try {
            foreach ($this->files as $fileHandler) {
                $fileHandlers[] = $fileHandler;
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error getting files: " . $e->getMessage(), 0, $e);
        }

        return $fileHandlers;
    }

    /**
     * Sort files by a specified attribute.
     *
     * @param string $attribute The attribute to sort by (name, size, mtime).
     * @return void
     * @throws InvalidArgumentException If an invalid attribute is provided.
     */
    public function sortFiles(string $attribute): void
    {
        $validAttributes = ['name', 'size', 'mtime'];
        if (!in_array($attribute, $validAttributes, true)) {
            throw new InvalidArgumentException("Invalid sort attribute: $attribute");
        }

        $sortedFiles = iterator_to_array($this->files);

        usort($sortedFiles, function (FileHandler $a, FileHandler $b) use ($attribute) {
            return $attribute === 'name'
                ? strcmp($a->getFilename(), $b->getFilename())
                : ($attribute === 'size'
                    ? $b->getSize() <=> $a->getSize()
                    : $b->getMTime() <=> $a->getMTime());
        });

        $this->files = new SplObjectStorage();
        foreach ($sortedFiles as $file) {
            $this->files->attach($file);
        }
    }

    /**
     * Filter files by a minimum size.
     *
     * @param int $minSize The minimum size in bytes.
     * @return void
     * @throws InvalidArgumentException If the minimum size is not valid.
     */
    public function filterFilesBySize(int $minSize): void
    {
        if ($minSize < 0) {
            throw new InvalidArgumentException("Invalid minimum size: $minSize");
        }

        foreach ($this->files as $file) {
            if ($file->getSize() < $minSize) {
                $this->files->detach($file);
            }
        }
    }

    /**
     * Filter files by a minimum modification date.
     *
     * @param int $minTimestamp The minimum modification date as a Unix timestamp.
     * @return void
     * @throws InvalidArgumentException If the timestamp is not valid.
     */
    public function filterFilesByDate(int $minTimestamp): void
    {
        if ($minTimestamp < 0) {
            throw new InvalidArgumentException("Invalid timestamp: $minTimestamp");
        }

        foreach ($this->files as $file) {
            if ($file->getMTime() < $minTimestamp) {
                $this->files->detach($file);
            }
        }
    }

    /**
     * Get subdirectories within the directory.
     *
     * @return string[] The array of subdirectory paths.
     * @throws RuntimeException If an error occurs while retrieving subdirectories.
     */
    public function getSubdirectories(): array
    {
        $subdirectories = [];

        try {
            $iterator = new DirectoryIterator($this->directory);

            foreach ($iterator as $fileInfo) {
                if ($fileInfo->isDir() && !$fileInfo->isDot()) {
                    $subdirectories[] = $fileInfo->getRealPath();
                }
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error getting subdirectories: " . $e->getMessage(), 0, $e);
        }

        return $subdirectories;
    }

    /**
     * Print the list of files with their metadata.
     *
     * @return void
     */
    public function printFiles(): void
    {
        try {
            foreach ($this->getFiles() as $fileHandler) {
                $metadata = $fileHandler->getMetadata();
                echo $metadata['pathname'] . ' (Size: ' . $metadata['size'] . ' bytes, Modified: ' . $metadata['lastModified'] . ')' . PHP_EOL;
            }
        } catch (Exception $e) {
            echo 'Error printing files: ' . $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * Get statistics about the directory.
     *
     * @return array The directory statistics.
     * @throws RuntimeException If an error occurs while calculating statistics.
     */
    public function getDirectoryStats(): array
    {
        $totalFiles = 0;
        $totalSize = 0;

        try {
            foreach ($this->files as $file) {
                $totalFiles++;
                $totalSize += $file->getSize();
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error calculating directory statistics: " . $e->getMessage(), 0, $e);
        }

        return [
            'totalFiles' => $totalFiles,
            'totalSize' => $totalSize,
        ];
    }

    /**
     * Delete all files in the directory.
     *
     * @return void
     * @throws RuntimeException If an error occurs while deleting files.
     */
    public function deleteAllFiles(): void
    {
        try {
            foreach ($this->getFiles() as $fileHandler) {
                $fileHandler->delete();
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error deleting files: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Copy all files to a new directory.
     *
     * @param string $destination The destination directory.
     * @return void
     * @throws RuntimeException If an error occurs while copying files.
     */
    public function copyAllFilesTo(string $destination): void
    {
        if (!is_dir($destination) || !is_writable($destination)) {
            throw new InvalidArgumentException("Destination not found or not writable: $destination");
        }

        try {
            foreach ($this->getFiles() as $fileHandler) {
                $fileHandler->copyTo($destination . DIRECTORY_SEPARATOR . basename($fileHandler->getPathname()));
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error copying files: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Move all files to a new directory.
     *
     * @param string $destination The destination directory.
     * @return void
     * @throws RuntimeException If an error occurs while moving files.
     */
    public function moveAllFilesTo(string $destination): void
    {
        if (!is_dir($destination) || !is_writable($destination)) {
            throw new InvalidArgumentException("Destination not found or not writable: $destination");
        }

        try {
            foreach ($this->getFiles() as $fileHandler) {
                $fileHandler->moveTo($destination . DIRECTORY_SEPARATOR . basename($fileHandler->getPathname()));
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error moving files: " . $e->getMessage(), 0, $e);
        }
    }
}

/*
// Example usage:
try {
    $directoryReader = new DirectoryReader('/path/to/directory', '/\.php$/');
    $directoryReader->filterFilesBySize(1024); // Filter files larger than 1KB
    $directoryReader->sortFiles('name'); // Sort files by name
    $directoryReader->printFiles();

    $stats = $directoryReader->getDirectoryStats();
    echo "Total Files: " . $stats['totalFiles'] . PHP_EOL;
    echo "Total Size: " . $stats['totalSize'] . " bytes" . PHP_EOL;
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
*/
// eof
