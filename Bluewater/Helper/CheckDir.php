<?php

namespace Bluewater\Helper;

use Bluewater\Exception\BluewaterDirException;

/**
 * Attempts to open a directory handle. If the operation fails,
 * @return resource|null Returns a resource, or null
 * @package BLUEWATER\Helper
 */
class CheckDir
{
    /**
     * Attempts to open a directory handle. If the operation fails, returns NULL
     *
     * @return resource|null Returns a resource, or null
     * @return resource Directory handle resource on success.
     * @throws BluewaterDirException
     */
    public function helper(array $args)
    {
        $dirPath = $args[0];
        $handle = @opendir($dirPath);
        if ($handle === false) {
            throw new BluewaterDirException($dirPath);
        }
        return $handle;
    }
}

// eof
