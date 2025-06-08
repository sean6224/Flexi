<?php
namespace Flexi\Dynamic\Exceptions;

use Exception;

class MethodException extends Exception
{
    public static function methodAlreadyExists(string $name): self
    {
        return new self("Method '$name' already exists in the registry.");
    }

    public static function methodNotFound(string $name): self
    {
        return new self("Method '$name' is not registered in the registry.");
    }

    public static function invalidMethodSignature(string $name): self
    {
        return new self("Invalid signature for method '$name'.");
    }

    public static function methodExecutionFailed(string $name, string $reason): self
    {
        return new self("Execution of method '$name' failed: $reason");
    }
}