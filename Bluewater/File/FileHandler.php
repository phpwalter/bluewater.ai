<?php

declare(strict_types=1);

namespace Bluewater\File;

use RuntimeException;
use SplFileObject;

/**
 * FileHandler class for handling individual file operations.
 *
 * This class extends SplFileObject to provide additional functionality
 * such as appending content, checking file existence, deleting files,
 * and retrieving file metadata.
 *
 * @package FileManager
 */
class FileHandler extends SplFileObject
{
    /**
     * FileHandler constructor.
     *
     * @param string $filePath The path to the file.
     * @throws RuntimeException If the file cannot be opened.
     */
    public function __construct(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new RuntimeException("File not found: $filePath");
        }

        try {
            parent::__construct($filePath, 'r+');
        } catch (Exception $e) {
            throw new RuntimeException("Unable to open file: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Read the entire file content.
     *
     * @return string The content of the file.
     * @throws RuntimeException If an error occurs while reading the file.
     */
    public function readFile(): string
    {
        try {
            $this->rewind();
            return $this->fread($this->getSize());
        } catch (Exception $e) {
            throw new RuntimeException("Error reading file: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Write content to the file.
     *
     * @param string $content The content to write.
     * @return void
     * @throws RuntimeException If an error occurs while writing to the file.
     */
    public function writeFile(string $content): void
    {
        try {
            $this->ftruncate(0); // Clear the file before writing
            $this->rewind();
            $this->fwrite($content);
        } catch (Exception $e) {
            throw new RuntimeException("Error writing to file: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Append content to the file.
     *
     * @param string $content The content to append.
     * @return void
     * @throws RuntimeException If an error occurs while appending to the file.
     */
    public function appendFile(string $content): void
    {
        try {
            $this->fseek(0, SEEK_END); // Move to the end of the file
            $this->fwrite($content);
        } catch (Exception $e) {
            throw new RuntimeException("Error appending to file: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Check if the file exists.
     *
     * @return bool True if the file exists, false otherwise.
     */
    public function exists(): bool
    {
        return file_exists($this->getPathname());
    }

    /**
     * Delete the file.
     *
     * @return void
     * @throws RuntimeException If the file cannot be deleted.
     */
    public function delete(): void
    {
        $filePath = $this->getPathname();
        if (!unlink($filePath)) {
            throw new RuntimeException("Failed to delete file: $filePath");
        }
    }

    /**
     * Check if the file is executable.
     *
     * @return bool True if the file is executable, false otherwise.
     */
    public function isExecutable(): bool
    {
        return is_executable($this->getPathname());
    }

    /**
     * Create a temporary file.
     *
     * @param string $prefix The prefix for the temporary file name.
     * @return FileHandler The temporary file object.
     * @throws RuntimeException If an error occurs while creating the temporary file.
     */
    public static function createTempFile(string $prefix = 'tmp'): FileHandler
    {
        try {
            $tempFilePath = tempnam(sys_get_temp_dir(), $prefix);
            return new self($tempFilePath);
        } catch (Exception $e) {
            throw new RuntimeException("Error creating temporary file: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Get the file's metadata.
     *
     * @return array The file metadata.
     * @throws RuntimeException If an error occurs while retrieving metadata.
     */
    public function getMetadata(): array
    {
        try {
            return [
                'pathname' => $this->getPathname(),
                'size' => $this->getSize(),
                'type' => $this->getType(),
                'isWritable' => $this->isWritable(),
                'isReadable' => $this->isReadable(),
                'isExecutable' => $this->isExecutable(),
                'lastModified' => date('Y-m-d H:i:s', $this->getMTime()),
            ];
        } catch (Exception $e) {
            throw new RuntimeException("Error getting file metadata: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Copy the file to a new location.
     *
     * @param string $destination The destination path.
     * @return void
     * @throws RuntimeException If an error occurs while copying the file.
     */
    public function copyTo(string $destination): void
    {
        try {
            if (!copy($this->getPathname(), $destination)) {
                throw new RuntimeException("Failed to copy file to: $destination");
            }
        } catch (Exception $e) {
            throw new RuntimeException("Error copying file: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Move the file to a new location.
     *
     * @param string $destination The destination path.
     * @return void
     * @throws RuntimeException If an error occurs while moving the file.
     */
    public function moveTo(string $destination): void
    {
        try {
            $this->copyTo($destination);
            $this->delete();
        } catch (Exception $e) {
            throw new RuntimeException("Error moving file: " . $e->getMessage(), 0, $e);
        }
    }
}

/*

<?php

require 'FileHandler.php'; // Ensure this path is correct

try {
    // Create a temporary file
    $fileHandler = FileHandler::createTempFile('example_');

    // Get the file path for reference
    $filePath = $fileHandler->getPathname();
    echo "Temporary file created: $filePath" . PHP_EOL;

    // Write content to the file
    $content = "Hello, World!";
    $fileHandler->writeFile($content);
    echo "Content written to file: $content" . PHP_EOL;

    // Read content from the file
    $readContent = $fileHandler->readFile();
    echo "Content read from file: $readContent" . PHP_EOL;

    // Append additional content to the file
    $additionalContent = " How are you today?";
    $fileHandler->appendFile($additionalContent);
    echo "Additional content appended to file: $additionalContent" . PHP_EOL;

    // Read the updated content from the file
    $updatedContent = $fileHandler->readFile();
    echo "Updated content read from file: $updatedContent" . PHP_EOL;

    // Get file metadata
    $metadata = $fileHandler->getMetadata();
    echo "File Metadata:" . PHP_EOL;
    echo "Pathname: " . $metadata['pathname'] . PHP_EOL;
    echo "Size: " . $metadata['size'] . " bytes" . PHP_EOL;
    echo "Type: " . $metadata['type'] . PHP_EOL;
    echo "Is Writable: " . ($metadata['isWritable'] ? 'Yes' : 'No') . PHP_EOL;
    echo "Is Readable: " . ($metadata['isReadable'] ? 'Yes' : 'No') . PHP_EOL;
    echo "Is Executable: " . ($metadata['isExecutable'] ? 'Yes' : 'No') . PHP_EOL;
    echo "Last Modified: " . $metadata['lastModified'] . PHP_EOL;

    // Delete the file
    $fileHandler->delete();
    echo "File deleted: $filePath" . PHP_EOL;
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}


 */

// eof
