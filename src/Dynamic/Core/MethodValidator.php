<?php
declare(strict_types=1);
namespace Flexi\Dynamic\Core;

use Flexi\Dynamic\Exceptions\MethodException;

/**
 * Validates method names, callbacks, and argument types for dynamically registered methods.
 *
 * This class ensures that:
 * - Method names follow valid naming conventions.
 * - Callbacks are callable.
 * - Method call arguments match the expected parameter types.
 */
class MethodValidator
{
    /**
     * @throws MethodException
     */
    public function validateMethodName(string $name): void
    {
        if (!preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $name))
        {
           throw MethodException::invalidMethodSignature($name);
        }
    }

    /**
     * @throws MethodException
     */
    public function validateMethodCall(MethodDefinition $definition, array $arguments): void
    {
        if (!$definition->validateArguments($arguments))
        {
           throw MethodException::invalidMethodSignature('Invalid argument types provided');
        }
    }

    /**
     * @throws MethodException
     */
    public function validateCallback(callable $callback): void
    {
        if (!is_callable($callback))
        {
           throw MethodException::invalidMethodSignature('Invalid callback provided');
        }
    }
}