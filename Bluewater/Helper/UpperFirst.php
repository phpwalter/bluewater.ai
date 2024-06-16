<?php

namespace Bluewater\Helper;

/**
 * Class Capitalize_First: Capitalizes the first letter of a string.
 * @package BLUEWATER\Helper
 */
class UpperFirst
{
    /**
     * Capitalize the first letter of a string.
     * @param array $args
     * @return string
     */
    public function helper(array $args): string
    {
        return ucfirst($args[0]);
    }
}

// eof
