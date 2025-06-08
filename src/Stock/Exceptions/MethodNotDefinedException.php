<?php
namespace Flexi\Stock\Exceptions;

use Exception;

/**
 * Class MethodNotDefinedException
 *
 * Exception thrown when a called method is not defined in the current context.
 */
class MethodNotDefinedException extends Exception
{
    public const string MESSAGE = "The method '%s' is not defined.";

    public function __construct(string $methodName)
    {
        $message = sprintf(self::MESSAGE, $methodName);
        parent::__construct($message);
    }
}