<?php
namespace Flexi\Stock\Exceptions;

use Exception;

/**
 * Class NoPublicMethodsException
 *
 * Exception thrown when a class contains no public methods available for registration.
 */
class NoPublicMethodsException extends Exception
{
    public const string MESSAGE = "The class '%s' has no public methods to register.";

    public function __construct(string $className)
    {
        $message = sprintf(self::MESSAGE, $className);
        parent::__construct($message);
    }
}