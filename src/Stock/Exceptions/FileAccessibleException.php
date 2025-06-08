<?php
namespace Flexi\Stock\Exceptions;

use Exception;

/**
 * Class FileAccessibleException
 *
 * Thrown when a file is not accessible (e.g., due to permissions or missing file).
 */
class FileAccessibleException extends Exception
{
    public const string MESSAGE = "File %s is not accessible.";

    public function __construct(string $filePath)
    {
        $message = sprintf(self::MESSAGE, $filePath);
        parent::__construct($message);
    }
}