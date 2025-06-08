<?php
namespace Flexi\DynamicSet\Exception;

use Exception;

/**
 * Class InvalidPropertyException
 *
 * Exception thrown when attempting to set or access
 * a property that does not exist in the target class.
 *
 * @package Flexi\DynamicSet\Exception
 */
class InvalidPropertyException extends Exception
{
    public function __construct(string $property, string $className)
    {
        $message = "Property '$property' does not exist in " . $className;
        parent::__construct($message);
    }
}