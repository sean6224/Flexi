<?php
namespace Flexi\Stock\Exceptions;

use Exception;

/**
 * Class FileEmptyOrNotReadException
 *
 * Thrown when a file is empty or could not be read.
 */
class FileEmptyOrNotReadException extends Exception
{
    public const string MESSAGE = "File %s is empty or could not be read.";

    public function __construct(string $filePath)
    {
        $message = sprintf(self::MESSAGE, $filePath);
        parent::__construct($message);
    }
}