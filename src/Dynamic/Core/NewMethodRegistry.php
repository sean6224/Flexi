<?php
declare(strict_types=1);
namespace Flexi\Dynamic\Core;

use Closure;
use Flexi\Dynamic\Contracts\DynamicMethodRegistryInterface;
use Flexi\Dynamic\Exceptions\MethodException;

/**
 * Registry for dynamically adding, removing, and invoking methods with validation.
 *
 * This class:
 * - Validates method names and callbacks before registration.
 * - Prevents duplicate method registrations.
 * - Invokes methods with argument and return type validation via MethodInvoker.
 * - Allows retrieval of method metadata and definitions.
 */
class NewMethodRegistry implements DynamicMethodRegistryInterface
{
    private array $methods = [];
    private MethodValidator $validator;
    private MethodInvoker $invoker;

    public function __construct()
    {
        $this->validator = new MethodValidator();
        $this->invoker = new MethodInvoker($this->validator);
    }

    /**
     * @throws MethodException
     */
    public function addMethod(
        string $name,
        Closure $callback,
        array $metadata = [],
        ?string $returnType = null,
        array $parameterTypes = []
    ): void
    {
        $this->validator->validateMethodName($name);
        $this->validator->validateCallback($callback);

        if ($this->hasMethod($name))
        {
            throw MethodException::methodAlreadyExists($name);
        }

        $this->methods[$name] = new MethodDefinition(
            $callback,
            $metadata,
            $returnType,
            $parameterTypes
        );
    }

    public function removeMethod(string $name): void
    {
        unset($this->methods[$name]);
    }

    /**
     * @throws MethodException
     */
    public function callMethod(string $name, array $arguments): mixed
    {
        if (!$this->hasMethod($name))
        {
            throw MethodException::methodNotFound($name);
        }

        return $this->invoker->invoke($this->methods[$name], $arguments);
    }

    public function getMethods(): array
    {
        return array_keys($this->methods);
    }

    public function hasMethod(string $name): bool
    {
        return isset($this->methods[$name]);
    }

    public function getMethodMetadata(string $name): ?array
    {
        if (!$this->hasMethod($name))
        {
            return null;
        }

        return $this->methods[$name]->getMetadata();
    }

    public function getMethodDefinition(string $name): ?MethodDefinition
    {
        return $this->methods[$name] ?? null;
    }
}