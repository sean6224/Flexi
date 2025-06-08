<?php
namespace Flexi\Dynamic\Core;

use Closure;
use Flexi\Dynamic\Utils\TypeNormalizer;

/**
 * Represents the definition of a dynamically registered method.
 *
 * This class encapsulates the method callback along with optional metadata,
 * expected return type, and parameter types for argument validation.
 *
 * The class supports argument type validation against expected parameter types,
 * including normalization of common PHP type aliases.
 *
 * @readonly
 */
readonly class MethodDefinition
{
    public function __construct(
        private Closure $callback,
        private array $metadata = [],
        private ?string $returnType = null,
        private array $parameterTypes = []
    ) {
    }

    public function getCallback(): Closure
    {
        return $this->callback;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getReturnType(): ?string
    {
        return $this->returnType;
    }

    public function getParameterTypes(): array
    {
        return $this->parameterTypes;
    }

    public function validateArguments(array $arguments): bool
    {
        if (empty($this->parameterTypes)) {
            return true;
        }

        foreach ($arguments as $index => $argument)
        {
            if (!isset($this->parameterTypes[$index])){
                continue;
            }

            $expectedType = $this->parameterTypes[$index];
            if (!$this->isValidType($argument, $expectedType))
            {
                return false;
            }
        }

        return true;
    }

    private function isValidType($value, string $expectedType): bool
    {
        $expectedType = TypeNormalizer::normalizeType($expectedType);

        if ($expectedType === 'mixed')
        {
            return true;
        }
        
        $actualType = gettype($value);
        if ($actualType === 'object')
        {
            return $value instanceof $expectedType;
        }

        return $actualType === $expectedType;
    }
}