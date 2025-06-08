<?php
namespace Flexi\Stock\Exceptions;

use Exception;

/**
 * Class InvalidOverrideFormatException
 *
 * Exception thrown when the override format is invalid.
 */
class InvalidOverrideFormatException extends Exception
{
    public const string MESSAGE = "Invalid override format: %s.";

    public function __construct(string $reason)
    {
        $message = sprintf(self::MESSAGE, $reason);
        parent::__construct($message);
    }
}