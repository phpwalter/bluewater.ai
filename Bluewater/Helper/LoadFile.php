<?php

namespace Bluewater\Helper;

use Bluewater\Exception\BluewaterFileException;

/**
 * Attempts to open a directory handle. If the operation fails,
 * @return resource|null Returns a resource, or null
 * @package BLUEWATER\Helper
 */
class LoadFile
{
    /**
     * Attempts to load a file. If the operation fails, returns NULL
     *
     * @return string|null Returns a resource, or null
     * @return resource Directory handle resource on success.
     * @throws BluewaterFileException
     */
    public function helper(array $args): ?string
    {
        $filePath = $args[0];
        $content = @file_get_contents($filePath);
        if ($content === false) {
            throw new BluewaterFileException("Failed to open file: '{$filePath}'");
        }
        return $content;
    }
}

// eof
