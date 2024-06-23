<?php

declare(strict_types=1);

namespace Bluewater\Exception;

use Exception;

/**
 * Class BluewaterDirException
 *
 * Custom exception class for handling directory-specific exceptions in Bluewater application.
 *
 * @package Bluewater\Exception
 */
class BluewaterDirException extends Exception
{
    /**
     * Error code for the exception.
     *
     * @var int|null
     */
    private ?int $errorCode;

    /**
     * Original exception.
     *
     * @var Exception|null
     */
    private ?Exception $originalException;

    /**
     * Custom error message for the exception.
     *
     * @var string
     */
    private string $errorMessage;

    /**
     * BluewaterDirException constructor.
     *
     * @param string $errorMessage Custom message for the exception.
     * @param int|null $errorCode Custom error code for the exception.
     * @param Exception|null $originalException Original exception.
     */
    public function __construct(string $errorMessage, int $errorCode = 0, ?Exception $originalException = null)
    {
        parent::__construct($errorMessage, $errorCode);

        $this->errorCode = $errorCode;
        $this->originalException = $originalException;
        $this->errorMessage = $errorMessage;

        echo '<hr>';
        echo $this->errorCode, "<br>";
        echo $this->originalException, "<br>";
        echo $this->errorMessage, "<br>";
        echo '<hr>';
    }

}
// eof
