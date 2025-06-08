<?php
namespace Flexi\DynamicSet\Exception;

use Exception;

/**
 * Class ValidationException
 *
 * Exception thrown when validation of a property or object fails.
 *
 * @package Flexi\DynamicSet\Exception
 */
class ValidationException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}