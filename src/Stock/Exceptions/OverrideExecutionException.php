<?php
namespace Flexi\Stock\Exceptions;

use Exception;

/**
 * Class OverrideExecutionException
 *
 * Thrown when an error occurs during the execution of an overridden method.
 */
class OverrideExecutionException extends Exception
{
    public const string MESSAGE = "Error while executing overridden method '%s': %s";

    public function __construct(string $methodName, string $error)
    {
        $message = sprintf(self::MESSAGE, $methodName, $error);
        parent::__construct($message);
    }
}