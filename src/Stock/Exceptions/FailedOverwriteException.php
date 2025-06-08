<?php
namespace Flexi\Stock\Exceptions;

use Exception;

/**
 * Class FailedOverwriteException
 *
 * Thrown when the system fails to overwrite a file with the modified content.
 */
class FailedOverwriteException extends Exception
{
    public const string MESSAGE = "Failed to overwrite %s with the modified content.";

    public function __construct(string $filePath)
    {
        $message = sprintf(self::MESSAGE, $filePath);
        parent::__construct($message);
    }
}