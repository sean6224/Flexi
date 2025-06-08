<?php
declare(strict_types=1);
namespace Flexi\Dynamic\Traits;

use Closure;
use Flexi\Dynamic\Core\NewMethodRegistry;
use Flexi\Dynamic\Exceptions\MethodException;

/*
use Flexi\Dynamic\Traits\DynamicMethodAwareTrait;

class MagicService
{
    use DynamicMethodAwareTrait;

    public function __construct()
    {
        $this->registerMethod(
            'greet',
            fn(string $name): string => "Hello, {$name}",
            ['description' => 'Greets the user'],
            'string',
            ['string']
        );
    }
}

$service = new MagicService();
echo $service->greet('Kacper'); // Hello, Kacper


*/


/**
 * Trait DynamicMethodAwareTrait
 *
 * Provides dynamic method registration and invocation capabilities.
 * Allows attaching methods at runtime with optional metadata, return types,
 * and parameter type definitions.
 *
 * @package Flexi\Dynamic\Traits
 */
trait DynamicMethodAwareTrait
{
    private ?NewMethodRegistry $methodRegistry = null;

    protected function getMethodRegistry(): NewMethodRegistry
    {
        if ($this->methodRegistry === null) {
            $this->methodRegistry = new NewMethodRegistry();
        }
        return $this->methodRegistry;
    }

    /**
     * @throws MethodException
     */
    public function registerMethod(
        string $name,
        Closure $callback,
        array $metadata = [],
        ?string $returnType = null,
        array $parameterTypes = []
    ): void {
        $this->getMethodRegistry()->addMethod($name, $callback, $metadata, $returnType, $parameterTypes);
    }

    /**
     * @throws MethodException
     */
    public function callMethod(string $name, array $arguments = []): mixed
    {
        return $this->getMethodRegistry()->callMethod($name, $arguments);
    }

    public function getMethodMetadata(string $name): ?array
    {
        return $this->getMethodRegistry()->getMethodMetadata($name);
    }

    public function getMethods(): array
    {
        return $this->getMethodRegistry()->getMethods();
    }
    /**
     * @throws MethodException
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->getMethodRegistry()->callMethod($name, $arguments);
    }

    public function __isset(string $name): bool
    {
        return $this->getMethodRegistry()->hasMethod($name);
    }
}