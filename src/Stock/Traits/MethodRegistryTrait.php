<?php
declare(strict_types=1);
namespace Flexi\Stock\Traits;

use Flexi\Exceptions\MethodRegistryException;
use Flexi\Stock\Contracts\MethodRegistryInterface;
use Flexi\Stock\Core\MethodRegistry;

/**
 * Trait MethodRegistryTrait
 *
 * Provides integration with a method registry system for managing dynamic method calls.
 *
 * This trait allows an object to register itself with a method registry and delegate dynamic method
 * execution to that registry. It encapsulates the setup and access to the registry instance and exposes
 * a convenient `call()` method to invoke registered methods by name.
 *
 * It supports:
 * - One-time initialization of the registry via {@see initMethodRegistry()}.
 * - Invocation of dynamically registered methods via {@see call()}.
 * - Access to the registry instance via {@see getMethodRegistry()}.
 * @throws MethodRegistryException If the registry fails to register the current instance.
 */
trait MethodRegistryTrait
{
    private MethodRegistryInterface $registry;

    /**
     * @throws MethodRegistryException
     */
    protected function initMethodRegistry(): void
    {
        $this->registry = new MethodRegistry();
        $this->registry->register($this);
    }

    public function call(string $name, ...$arguments): mixed
    {
        return $this->registry->call($name, ...$arguments);
    }

    public function getMethodRegistry(): MethodRegistryInterface
    {
        return $this->registry;
    }
}